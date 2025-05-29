ALTER TABLE users ADD COLUMN organization_id INT NULL;
ALTER TABLE users ADD CONSTRAINT fk_organization FOREIGN KEY (organization_id) REFERENCES organization(id);

INSERT INTO organization (name) SELECT 'innowise' WHERE NOT EXISTS (
    SELECT 1 FROM organization WHERE name = 'innowise'
);

UPDATE users SET organization_id = (SELECT id FROM organization WHERE name = 'innowise') WHERE organization_id IS NULL;
