-- =====================================================================
-- Seed script: Basic English Grammar (L1) Module Quiz (30 questions)
-- Run once in phpMyAdmin or MySQL CLI.
--   SET @module_id = 3;  (3 = Basic English Grammar L1)
-- =====================================================================

SET @module_id = 3;  -- <-- Basic English Grammar (L1) module

-- 1. Create the quiz (randomized questions per attempt)
INSERT INTO quizzes (module_id, quiz_title, passing_score, question_limit, time_limit, random_questions, random_answers)
VALUES (@module_id, 'Basic English Grammar (L1) Module Quiz', 70, 10, 0, 1, 1);

SET @quiz_id = LAST_INSERT_ID();

-- =====================================================================
-- Questions
-- =====================================================================
INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What is a noun?', 'A noun is a person, place, thing, or idea.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'An action word', 0, 0), (@q, 'A person, place, thing, or idea', 1, 1), (@q, 'A describing word', 0, 2), (@q, 'A glue word', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'Find the verb in: "The dog runs fast."', '"Runs" is the action word.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'Dog', 0, 0), (@q, 'Fast', 0, 1), (@q, 'Runs', 1, 2), (@q, 'The', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'Which word is a personal pronoun?', '"He" replaces a person''s name.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'Table', 0, 0), (@q, 'He', 1, 1), (@q, 'Quickly', 0, 2), (@q, 'Under', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What is the plural of "box"?', 'Nouns ending in -x add -es: "boxes".');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'Boxs', 0, 0), (@q, 'Boxes', 1, 1), (@q, 'Boxies', 0, 2), (@q, 'Boxen', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'Which article goes before a word starting with a vowel sound (like "apple")?', 'Use "an" before vowel sounds: "an apple".');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'A', 0, 0), (@q, 'An', 1, 1), (@q, 'The only', 0, 2), (@q, 'None', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'Choose the correct "to be" verb: "He ___ a student."', 'He/She/It takes "is".');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'am', 0, 0), (@q, 'are', 0, 1), (@q, 'is', 1, 2), (@q, 'be', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'Find the adjective in: "The red car is fast."', '"Red" describes the car.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'Car', 0, 0), (@q, 'Is', 0, 1), (@q, 'Red', 1, 2), (@q, 'The', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What is the past tense of "go"?', '"Go" is irregular: go - went.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'goed', 0, 0), (@q, 'going', 0, 1), (@q, 'went', 1, 2), (@q, 'gone', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'Which preposition do we use for days (like Monday)?', 'Days take "on": "on Monday".');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'In', 0, 0), (@q, 'At', 0, 1), (@q, 'On', 1, 2), (@q, 'Under', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'Make this negative: "I like coffee."', 'Present Simple negatives use "do not + base verb".');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'I am not like coffee.', 0, 0), (@q, 'I do not like coffee.', 1, 1), (@q, 'I does not like coffee.', 0, 2), (@q, 'I no like.', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What is a verb?', 'A verb is an action word or state of being.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'A name', 0, 0), (@q, 'An action word or state of being', 1, 1), (@q, 'A color', 0, 2), (@q, 'A place', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'Which word is a preposition of place?', '"Under" tells where something is.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'Quickly', 0, 0), (@q, 'Under', 1, 1), (@q, 'Happy', 0, 2), (@q, 'Yesterday', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'Turn this into a question: "You are happy."', 'Move "are" to the front: "Are you happy?"');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'You are happy?', 0, 0), (@q, 'Are you happy?', 1, 1), (@q, 'Happy you are?', 0, 2), (@q, 'Do you happy?', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What is the comparative form of "big"?', 'Short adjectives add -er: "bigger".');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'More big', 0, 0), (@q, 'Bigger', 1, 1), (@q, 'Biggest', 0, 2), (@q, 'Biggerer', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What is the superlative form of "tall"?', 'Short adjectives add -est: "tallest".');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'Taller', 0, 0), (@q, 'Tallest', 1, 1), (@q, 'Most tall', 0, 2), (@q, 'More tall', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'Which conjunction means a choice?', '"Or" gives a choice.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'And', 0, 0), (@q, 'But', 0, 1), (@q, 'Or', 1, 2), (@q, 'So', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What does the Present Simple tense show?', 'Present Simple shows habits and facts.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'Habits and facts', 1, 0), (@q, 'Only what is happening right this second', 0, 1), (@q, 'Something that happened last year', 0, 2), (@q, 'Future dreams only', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'Choose the correct word: "This is John''s book. It is ___ book."', 'John is male singular, so use "his".');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'her', 0, 0), (@q, 'his', 1, 1), (@q, 'their', 0, 2), (@q, 'my', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What is the plural of "baby"?', 'Consonant + y becomes -ies: "babies".');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'Babys', 0, 0), (@q, 'Babies', 1, 1), (@q, 'Babies''', 0, 2), (@q, 'Baby''s', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'Find the adverb in: "She sings beautifully."', '"Beautifully" describes how she sings.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'She', 0, 0), (@q, 'Sings', 0, 1), (@q, 'Beautifully', 1, 2), (@q, 'None', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'Which sentence is in the Present Continuous tense (right now)?', '"Am/is/are + verb-ing": "I am playing football right now."');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, '"I played football."', 0, 0), (@q, '"I play football on Sundays."', 0, 1), (@q, '"I am playing football right now."', 1, 2), (@q, '"I will play football."', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What is the past tense of "play"?', 'Regular verbs add -ed: "played".');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'Playing', 0, 0), (@q, 'Played', 1, 1), (@q, 'Plays', 0, 2), (@q, 'Playd', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'Which word is a demonstrative pronoun?', '"This" points to something near.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'Quickly', 0, 0), (@q, 'This', 1, 1), (@q, 'Because', 0, 2), (@q, 'Table', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'Fill in the blank: "There ___ three pens on the desk."', 'Plural nouns take "are".');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'is', 0, 0), (@q, 'am', 0, 1), (@q, 'are', 1, 2), (@q, 'be', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What is the opposite of "hot"?', '"Cold" is the opposite of "hot".');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'Warm', 0, 0), (@q, 'Cold', 1, 1), (@q, 'Cool', 0, 2), (@q, 'Bright', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'Which word shows possession (ownership)?', '"My" shows ownership.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'Quickly', 0, 0), (@q, 'My', 1, 1), (@q, 'Run', 0, 2), (@q, 'Under', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'Which modal verb shows ability?', '"Can" shows ability.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'Must', 0, 0), (@q, 'Can', 1, 1), (@q, 'Should', 0, 2), (@q, 'Might', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What punctuation mark ends a question?', 'Questions end with a question mark (?).');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'Period (.)', 0, 0), (@q, 'Comma (,)', 0, 1), (@q, 'Question mark (?)', 1, 2), (@q, 'Exclamation mark (!)', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'Which word is an uncountable noun?', '"Water" cannot be counted individually.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'Chair', 0, 0), (@q, 'Apple', 0, 1), (@q, 'Water', 1, 2), (@q, 'Car', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What is the short form (contraction) of "do not"?', 'do not = don''t.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'Doesn''t', 0, 0), (@q, 'Don''t', 1, 1), (@q, 'Didn''t', 0, 2), (@q, 'Aren''t', 0, 3);
