FROM dart:stable AS build
WORKDIR /app
COPY task-management/lib ./lib
COPY task-management/pubspec.yaml ./
RUN dart pub get
RUN dart compile exe lib/main.dart -o /app/task-app

FROM debian:stable-slim
COPY --from=build /app/task-app /app/task-app
EXPOSE 8080
CMD ["/app/task-app"]
