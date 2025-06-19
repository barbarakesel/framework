CREATE TABLE IF NOT EXISTS "user" (
                              id SERIAL PRIMARY KEY,
                              email VARCHAR(255),
                              password VARCHAR(255)
                              );