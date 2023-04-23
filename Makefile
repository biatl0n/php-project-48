install:
	composer install
lint:
	composer exec --verbose phpcs -- --standard=PSR12 src bin
validate:
	composer validate
test-coverage:
	composer exec phpunit tests -- --coverage-clover build/logs/clover.xml
