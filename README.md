# Advent of Code 2023

https://adventofcode.com/

### Building app
- Start the container: `docker compose up --build -d`
- Open shell inside the container: `docker exec -it aoc23 bash`
- Follow container logs: `docker compose logs -f`

### Commands inside the container
- Solve task: `bin/console <day> [<part>]`, where `<part>` is `A|B`
- Enable debugger with `xdt bin/console ...` (`xdt` for `XDEBUG_TRIGGER=`)
- Run tests: `[xdt] phpunit [--filter 01-B]`
  - `FAST= phpunit` to skip long-running tests
- Run static analysis: `phpstan`
