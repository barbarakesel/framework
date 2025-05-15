CREATE TABLE public.users (
                              id SERIAL PRIMARY KEY,
                              country VARCHAR(255),
                              city VARCHAR(255),
                              is_active BOOLEAN,
                              gender VARCHAR(10),
                              birth_date DATE,
                              salary INTEGER,
                              has_children BOOLEAN,
                              family_status VARCHAR(50),
                              registration_date DATE
);