# Advent of Code 2023

### Building app
- Start the container: `docker compose up --build -d`
- Open shell inside the container: `docker exec -it aoc23 bash`
- Follow container logs: `docker compose logs -f`

### Commands inside the container
- Enable debugger with `xdt bin/console ...` (`xdt` for `XDEBUG_TRIGGER=`)
