./vendor/bin/phpunit
php ./bin/console db.migrations.migrate
php ./bin/console app.dto.creator  --baseNamespace='App' --baseDir='src' --sourcePath='config/packages/dto'
php ./bin/console app.validation.schema.creator --baseNamespace='App\Validation\Schema' --baseDir='src/Validation/Schema' --sourcePath='validationSchema'
rr serve -w /code -c /code/.rr.yaml
