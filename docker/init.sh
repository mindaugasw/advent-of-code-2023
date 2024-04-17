set -e

./composer.phar install

echo "Initialization complete"

# tail needed to keep container running
tail -f /dev/null
