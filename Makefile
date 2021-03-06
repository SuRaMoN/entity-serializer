
.PHONY: test recent-composer vendor vendor-update clean

vendor: recent-composer
	bin/composer.phar install

vendor/autoload.php: recent-composer
	bin/composer.phar install

test: vendor/autoload.php
	vendor/bin/phpunit

vendor-update: recent-composer
	bin/composer.phar update

clean:
	rm -fR bin/composer.phar vendor

recent-composer: bin/composer.phar
	if bin/composer.phar about | grep -q "This development build of composer is over 30 days old"; then \
		bin/composer.phar self-update; \
	fi

bin/composer.phar:
	mkdir -p bin
	wget -O bin/composer.phar.partial http://getcomposer.org/composer.phar
	mv bin/composer.phar.partial bin/composer.phar
	chmod a+x bin/composer.phar


