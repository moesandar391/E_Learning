-- =====================================================================
-- Seed script: English Vocabulary & Grammar Quiz (10 questions)
-- Run once in phpMyAdmin or MySQL CLI.
--   SET @module_id = <id>;  before running, or edit the value below.
-- =====================================================================

SET @module_id = 4;  -- <-- change to the module this quiz belongs to (e.g. 4 = Basic English Grammar L2)

-- 1. Create the quiz
INSERT INTO quizzes (module_id, quiz_title, passing_score, question_limit, time_limit, random_questions, random_answers)
VALUES (@module_id, 'English Vocabulary & Grammar Quiz', 70, 10, 0, 1, 1);

SET @quiz_id = LAST_INSERT_ID();

-- 2. Questions + options
INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'We put all our clothes in the ____________ at the end of the day.', 'A wardrobe is a tall cupboard where you hang or store clothes.');
SET @q1 = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q1, 'wardrobe', 1, 0), (@q1, 'fridge', 0, 1), (@q1, 'torch', 0, 2), (@q1, 'blanket', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'A person who flies a plane is called a ____________.', 'A pilot is someone who flies an aircraft.');
SET @q2 = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q2, 'Teacher', 0, 0), (@q2, 'Pilot', 1, 1), (@q2, 'Dentist', 0, 2), (@q2, 'Farmer', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'Which animal can jump very high and has a pouch for its baby?', 'A kangaroo is famous for its strong legs and the pouch where it carries its baby.');
SET @q3 = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q3, 'Sheep', 0, 0), (@q3, 'Wolf', 0, 1), (@q3, 'Kangaroo', 1, 2), (@q3, 'Crocodile', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'Yesterday, Sara ____________ a letter to her grandma.', '"Yesterday" is past time, so we use "wrote".');
SET @q4 = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q4, 'write', 0, 0), (@q4, 'wrote', 1, 1), (@q4, 'writing', 0, 2), (@q4, 'written', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'An elephant is ____________ than a mouse.', 'With "than" we use the comparative form "bigger".');
SET @q5 = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q5, 'biggest', 0, 0), (@q5, 'big', 0, 1), (@q5, 'bigger', 1, 2), (@q5, 'most big', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'Look! The children ____________ football in the park.', '"The children" (plural) doing something now = "are playing".');
SET @q6 = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q6, 'play', 0, 0), (@q6, 'is playing', 0, 1), (@q6, 'are playing', 1, 2), (@q6, 'plays', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'Tom goes to the beach every Sunday. He takes his blue surfboard and swims for one hour before lunch. When does Tom go to the beach?', 'The first sentence says "every Sunday".');
SET @q7 = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q7, 'Every Saturday', 0, 0), (@q7, 'Every Sunday', 1, 1), (@q7, 'Every month', 0, 2), (@q7, 'Only in summer', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'The cat is sleeping ____________ the sofa.', '"Under the sofa" is the natural preposition for a cat sleeping beneath it.');
SET @q8 = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q8, 'under', 1, 0), (@q8, 'through', 0, 1), (@q8, 'between', 0, 2), (@q8, 'across', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'In the kitchen we cook food using the ____________.', 'A cooker is the appliance you use to cook food.');
SET @q9 = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q9, 'fridge', 0, 0), (@q9, 'shower', 0, 1), (@q9, 'cooker', 1, 2), (@q9, 'bookshelf', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, '____________ bag is this? It''s mine.', '"Whose" asks about possession, so "Whose bag is this?" is correct.');
SET @q10 = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q10, 'Who', 0, 0), (@q10, 'Which', 0, 1), (@q10, 'Whose', 1, 2), (@q10, 'What', 0, 3);
