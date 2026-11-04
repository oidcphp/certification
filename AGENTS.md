# AGENTS.md

## Purpose

This repository is an OpenID Connect Relying Party (RP) certification harness for [`oidc/core`](https://github.com/oidcphp/core). Each route runs one RP test case of the OpenID certification RP test tool and shows the result. The goal is to test the behavior of `oidc/core` itself.

- Do not work around `oidc/core` bugs in this repository, for example by verifying ID Token claims yourself. That would hide the gap the certification is meant to catch. Report the bug to `oidcphp/core` and mark the related test incomplete with the reason.
- Workarounds for missing conveniences are fine when they do not hide verification gaps. For example, `RelyingParty` appends the well-known path and sends `nonce` explicitly.

## Stack

- PHP 8.4+, Laravel 12 (slim application structure), `oidc/core` 0.17, Laravel Dusk 8, PHPUnit 12
- There is no database. Session and cache use the `file` driver.
- Laravel 13 is blocked: `oidc/core` depends on `web-token/jwt-library` 3.x, which conflicts with the `brick/math` version Laravel 13 needs. See oidcphp/core#13.

## Layout

| Path | Description |
| --- | --- |
| `app/Certification/RelyingParty.php` | The whole RP flow: discovery, dynamic registration, authorization redirect, callback handling, UserInfo |
| `app/Http/Controllers/Rp/Code/*` | One invokable controller per Code flow test ID, each only calls `RelyingParty::redirect()` |
| `app/Http/Controllers/Rp/Configuration/*` | Configuration profile tests |
| `app/Http/Controllers/Callback*.php` | Redirect URI handlers that show the ID Token claims, the token response, or the UserInfo response as JSON |
| `app/Providers/AppServiceProvider.php` | Binds the HTTP client and the `ClientMetadata` used for dynamic registration |
| `config/certification.php` | Test tool base URL, RP ID, and contacts |
| `routes/web.php` | Test routes and named callback routes |
| `tests/Browser/*` | Dusk tests, one per test ID |

## Commands

```sh
composer install
cp .env.example .env
php artisan key:generate

php vendor/bin/phpcs               # lint (PSR-12), run before committing
php artisan route:list             # quick check that the app boots

php artisan serve --port=8000      # the app must run on the APP_URL used by the tests
php artisan dusk:chrome-driver --detect
vendor/bin/phpunit -c phpunit.dusk.xml
```

- `make test` does not work yet, see #19.
- Dusk needs Google Chrome. CI only runs lint and `route:list`.

## Adding a test case

1. Add an invokable controller in `app/Http/Controllers/Rp/<Profile>/` that calls `$rp->redirect('<profile>', '<test-id>', '<callback route name>')`. Pass extra authorization parameters, such as `scope`, as the fourth argument.
2. Register the route in `routes/web.php` under the matching `rp/<profile>` prefix. The path is the test ID.
3. Add a Dusk test in `tests/Browser/`. Add a docblock with the test description and expected result from the test tool, `@see` to the controller, and `@link` to the test log and the related spec section. Add `@see` to the test in the controller docblock.

Callback paths are only defined in `routes/web.php`. Refer to them by route name (`callback`, `callback.id-token`, `callback.token-set`, `callback.user-info`). The registered `redirect_uris` are derived from the same names.

## `oidc/core` behavior to know

- `Issuer::discover($uri)` sends a GET request to `$uri` as is. Pass the full `/.well-known/openid-configuration` URL (oidcphp/core#16).
- `Client` adds `state` to the authorization request but not `nonce`, so `RelyingParty` sends `nonce` explicitly.
- `Client::handleCallback()` does not verify the `nonce` claim (oidcphp/core#12). `testNonceInvalid` is incomplete until it is fixed.
- Unsigned ID Tokens (`alg=none`) are rejected (oidcphp/core#14). `testIdTokenSigNone` is incomplete.
- Every value passed as `$checks` to `handleCallback()` is also sent to the token endpoint and copied into the `TokenSet` (oidcphp/core#15). Only pass `state` and `redirect_uri`.
- Invalid claims throw `RelyingPartyException` with `Relying party info is invalid: ...`. Signature failures throw `OpenIDProviderException` with `Receive an invalid ID token: ...`. Assert the message that matches the failure.

## Certification tool status

The tests target the old RP test tool `https://rp.certification.openid.net:8080` (`{base_url}/{rp_id}.{profile}/{test_id}`), which currently times out. Migration to the new conformance suite is tracked in #21, and a mock OP for local and CI runs in #22.

## Conventions

- Write everything in the repository and on GitHub in English, including files, commit messages, issues, pull requests, releases, and reports.
- Follow PSR-12. `phpcs.xml.dist` checks `app`, `config`, `routes`, and `tests`.
- Files end with a single newline.
- Do not commit `.env`, `vendor/`, or Dusk screenshots and console logs.
