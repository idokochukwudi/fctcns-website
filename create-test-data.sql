-- Create a test application
INSERT INTO applications (
    first_name, last_name, email, phone, program, entry_year,
    highest_qualification, personal_statement, status
) VALUES (
    'John',
    'Doe',
    'john.doe@example.com',
    '+2348012345678',
    'B.Sc Nursing',
    2025,
    'WASSCE',
    'I am passionate about nursing and want to make a difference in healthcare.',
    'pending'
);

-- Check the ID that was created
SELECT LAST_INSERT_ID() as new_id;
