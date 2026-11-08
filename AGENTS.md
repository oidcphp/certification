# AGENTS.md

The key words "MUST", "MUST NOT", "REQUIRED", "SHALL", "SHALL NOT", "SHOULD", "SHOULD NOT", "RECOMMENDED", "NOT RECOMMENDED", "MAY", and "OPTIONAL" in this document are to be interpreted as described in BCP 14 [RFC 2119](https://www.rfc-editor.org/rfc/rfc2119.html) [RFC 8174](https://www.rfc-editor.org/rfc/rfc8174.html) when, and only when, they appear in all capitals, as shown here.

## Purpose

This repository is an OpenID Connect Relying Party (RP) certification harness for [`oidc/core`](https://github.com/oidcphp/core). Each route runs one RP test case of the OpenID certification RP test tool and shows the result. The goal is to test the behavior of `oidc/core` itself.

- `oidc/core` bugs MUST NOT be worked around in this repository, for example by verifying ID Token claims yourself. That would hide the gap the certification is meant to catch. The bug MUST be reported to `oidcphp/core`, and the related test MUST be marked incomplete with the reason.
- Workarounds for missing conveniences MAY be added when they do not hide verification gaps. For example, `RelyingParty` appends the well-known path and sends `nonce` explicitly.

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

php vendor/bin/phpcs               # lint (PSR-12)
php artisan route:list             # quick check that the app boots

php artisan serve --port=8000      # serve on the APP_URL used by the tests
php artisan dusk:chrome-driver --detect
vendor/bin/phpunit -c phpunit.dusk.xml
```

- `make test` runs `phpcs` and then Dusk, so the app MUST already be served (`make server`).
- `phpcs` MUST pass before committing.
- The app MUST run on the `APP_URL` used by the Dusk tests.
- Dusk needs Google Chrome. CI only runs lint and `route:list`.

## Adding a test case

1. An invokable controller MUST be added in `app/Http/Controllers/Rp/<Profile>/` that calls `$rp->redirect('<profile>', '<test-id>', '<callback route name>')`. Extra authorization parameters, such as `scope`, MAY be passed as the fourth argument.
2. The route MUST be registered in `routes/web.php` under the matching `rp/<profile>` prefix, and the path MUST be the test ID.
3. A Dusk test MUST be added in `tests/Browser/`. Its docblock SHOULD contain the test description and expected result from the test tool, `@see` to the controller, and `@link` to the test log and the related spec section. The controller docblock SHOULD have `@see` to the test.

Callback paths MUST only be defined in `routes/web.php`, and MUST be referred to by route name (`callback`, `callback.id-token`, `callback.token-set`, `callback.user-info`). The registered `redirect_uris` are derived from the same names.

## `oidc/core` behavior to know

- `Issuer::discover($uri)` sends a GET request to `$uri` as is. Pass the full `/.well-known/openid-configuration` URL (oidcphp/core#16).
- `Client` adds `state` to the authorization request but not `nonce`, so `RelyingParty` sends `nonce` explicitly.
- `Client::handleCallback()` does not verify the `nonce` claim (oidcphp/core#12). `testNonceInvalid` is incomplete until it is fixed.
- Unsigned ID Tokens (`alg=none`) are rejected (oidcphp/core#14). `testIdTokenSigNone` is incomplete.
- The `sub` Claim of the UserInfo Response is not verified against the ID Token (oidcphp/core#35). `testUserinfoBadSubClaim` is incomplete.
- Every value passed as `$checks` to `handleCallback()` is also sent to the token endpoint and copied into the `TokenSet` (oidcphp/core#15). Only `state` and `redirect_uri` SHOULD be passed.
- Invalid claims throw `RelyingPartyException` with `Relying party info is invalid: ...`. Signature failures throw `OpenIDProviderException` with `Receive an invalid ID token: ...`. Tests MUST assert the message that matches the failure.

## Certification tool status

The tests target the old RP test tool `https://rp.certification.openid.net:8080` (`{base_url}/{rp_id}.{profile}/{test_id}`), which currently times out. Migration to the new conformance suite is tracked in #21, and a mock OP for local and CI runs in #22.

## Documentation

The user documentation of `oidc/core` lives in [oidcphp/docs](https://github.com/oidcphp/docs).

- When a change in this repository changes how the certification is run or what its results mean, for example the commands, the test tool, or the profiles covered, the related pages in `oidcphp/docs` MUST be updated in a pull request to that repository.
- When this repository finds that `oidc/core` behaves differently from what `oidcphp/docs` says, the gap MUST be reported to `oidcphp/core` or `oidcphp/docs`, whichever is wrong.

## Conventions

- Everything in the repository and on GitHub MUST be written in English, including files, commit messages, issues, pull requests, releases, and reports.
- In PHPDoc, references to code (FQSEN) MUST use `@see`, and references to websites (URI) MUST use `@link`. A description after the reference is OPTIONAL.
- RFCs MUST be linked to the RFC Editor HTML page, `https://www.rfc-editor.org/rfc/rfcNNNN.html`, with `#section-X.Y` for a section. `tools.ietf.org` MUST NOT be used, since it now redirects to an info page without the section anchor.
- Code MUST follow PSR-12. `phpcs.xml.dist` checks `app`, `config`, `routes`, and `tests`.
- Files MUST end with a single newline.
- `.env`, `vendor/`, and Dusk screenshots and console logs MUST NOT be committed.
