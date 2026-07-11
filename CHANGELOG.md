# Changelog

# 2.2.0 (Jul 10, 2026)
- `generateByTime` and `generateByTimeWindow` now reject a non-positive `$window` (previously an uncaught `DivisionByZeroError`) and a negative `$timestamp` (previously a silent, unintended counter), throwing `InvalidArgumentException` instead.
- `generateByCounter`, `generateByTime` and `generateByTimeWindow` accept an optional `$algorithm` parameter (defaults to `sha1`), enabling HMAC-SHA-256 and HMAC-SHA-512 TOTP per RFC 6238.
- Fixed dynamic truncation in `HOTPResult` to derive the RFC 4226 offset from the digest's actual final byte, so wider SHA-256/512 digests truncate correctly (no change for SHA-1).
- The time-based methods accept an optional `$startTime` parameter (RFC 6238 "T0", defaults to `0`) to set the Unix time from which time steps are counted. A `$startTime` later than `$timestamp` is rejected with `InvalidArgumentException`, as it would yield a negative time step.
- All new behavior is validated against the RFC 6238 Appendix B SHA-1, SHA-256 and SHA-512 test vectors. These additions are backwards compatible.

# 2.1.0 (Apr 13, 2026)
- Adopts PSR-12 syntax everywhere (:heart: [jrzepa](https://github.com/jrzepa) [#12](https://github.com/thecodedrift/hotp-php/commit/7578eb618f2bca4f6a7121aaa75ee2b4c6fe6a94))
- Expands version range for PHPUnit to ensure tests run against PHP 7.2 [#16](https://github.com/jakobo/hotp-php/pull/16)
- Bump various dev dependancies [f88730](https://github.com/thecodedrift/hotp-php/commit/f88730af7a14f7e3c513616f6fb8acf3f27d30b2)
- Test on newer PHP versions [#15](https://github.com/thecodedrift/hotp-php/pull/15) [86c624](https://github.com/thecodedrift/hotp-php/commit/86c624bf82272870549bef7b6bea199f633fceb0) [584992](https://github.com/thecodedrift/hotp-php/commit/58499240cf0a3a7725165d503284b0e311a441fa) [38775b](https://github.com/thecodedrift/hotp-php/commit/38775bde6167f43dfdb03cfac6d7d3216ad9599c)
- Enables easy local development via a docker container [#6403696](https://github.com/thecodedrift/hotp-php/commit/6403696)
- Minor code cleanup and removal of redundant operations [#25](https://github.com/thecodedrift/hotp-php/pull/25)

## Breaking Changes
- **Bump minimum version** - The minimum supported PHP version was raised from 7.2 to 8.0 [#25](https://github.com/thecodedrift/hotp-php/pull/25). We encourage you to update your PHP version to stay on top of potential security vulnerabilities and receive the latest performance and bug fixes.

# 2.0.0 (Dec 20, 2020)
- Code coverage reporting added (:heart: [reedy](https://github.com/reedy))
- Documentation updates and return type hints (:heart: [reedy](https://github.com/reedy))
- Github CI Support [ac0d8d](https://github.com/jakobo/hotp-php/commit/ac0d8d0d64adc5f7ef83952bde25425bf74184cf) (:heart: [legoktm](https://github.com/legoktm))

## Breaking Changes
- **Bump minimum version** - To stay current, the minimum version was bumped from 5.3.3 to 7.2. We encourage you to update your PHP version to stay on top of potential security vulnerabilities and receive the latest performance and bug fixes.

# 1.0.1 (Apr 11, 2019)
- Token replay mitigation [d24e0d](https://github.com/jakobo/hotp-php/commit/d24e0d021710718cb9104ffb5c6ffb447fce65ab) (:heart: [reedy](https://github.com/reedy))
- Created `composer.json` and made available through composer (:heart: [reedy](https://github.com/reedy))

# 1.0.0
- [reedy](https://github.com/reedy) joined the maintainer team
- Initial Release with a tag
