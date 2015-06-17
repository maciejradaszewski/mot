TESTS = tests/modules/**/*.js
REPORTER = dot

test:
	@NODE_ENV=test ../node_modules/.bin/mocha \
		--reporter $(REPORTER) \
		--growl \
		$(TESTS)

test-cov:
	make test REPORTER=html-cov > docs/coverage.html

docs: test-docs

test-docs:
	make test REPORTER=doc \
		| cat docs/head.html - docs/tail.html \
		> docs/test.html

clean:
	rm docs/coverage.html docs/test.html

.PHONY: test-cov test docs test-docs clean
