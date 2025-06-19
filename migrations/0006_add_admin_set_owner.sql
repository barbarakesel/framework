INSERT INTO "user" (email, password)
SELECT 'admin@a.com', '$2y$12$2HwH.Ic66nS8aQvXV3gkXehqJGqWVDf9d7RJNQ6mxMICcrkc.Usny'

WHERE NOT EXISTS (
    SELECT 1 FROM "user" WHERE email = 'admin@a.com'
);


UPDATE organization
SET owner = (
    SELECT id FROM "user" WHERE email = 'admin@a.com'
)
WHERE owner IS NULL;
