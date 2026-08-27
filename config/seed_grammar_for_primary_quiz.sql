-- =====================================================================
-- Seed script: Grammar for Primary Module Quiz (30 questions)
-- Run once in phpMyAdmin or MySQL CLI.
--   SET @module_id = 9;  (9 = Grammar for primary)
-- =====================================================================

SET @module_id = 9;  -- <-- Grammar for Primary module

INSERT INTO quizzes (module_id, quiz_title, passing_score, question_limit, time_limit, random_questions, random_answers)
VALUES (@module_id, 'Grammar for Primary Module Quiz', 70, 10, 0, 1, 1);

SET @quiz_id = LAST_INSERT_ID();

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What is a proper noun?', 'A proper noun names a specific person or place and is always capitalized.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'Any normal object name', 0, 0), (@q, 'A specific name for a person or place (always capitalized)', 1, 1), (@q, 'An action word', 0, 2), (@q, 'A describing word', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'Which one is a common noun?', '"City" is general; London, Mary, and Monday are specific names.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'London', 0, 0), (@q, 'Mary', 0, 1), (@q, 'City', 1, 2), (@q, 'Monday', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What punctuation goes at the end of an excited sentence?', 'An exclamation mark (!) shows excitement.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'Period (.)', 0, 0), (@q, 'Question mark (?)', 0, 1), (@q, 'Exclamation mark (!)', 1, 2), (@q, 'Comma (,)', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What is a pronoun?', 'A pronoun takes the place of a noun, like "he" or "she".');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'A word that takes the place of a noun (like "he" or "she")', 1, 0), (@q, 'An action word', 0, 1), (@q, 'A punctuation mark', 0, 2), (@q, 'A spelling error', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'Find the verb in: "The sun shines."', '"Shines" is the action.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'Sun', 0, 0), (@q, 'Shines', 1, 1), (@q, 'The', 0, 2), (@q, 'None', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What is the plural of "tooth"?', 'Irregular plural: tooth -> teeth.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'Tooths', 0, 0), (@q, 'Teeth', 1, 1), (@q, 'Toothes', 0, 2), (@q, 'Teeths', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What is the plural of "foot"?', 'Irregular plural: foot -> feet.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'Foots', 0, 0), (@q, 'Feets', 0, 1), (@q, 'Feet', 1, 2), (@q, 'Footen', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'Which preposition of time is used for exact hours (like 5 PM)?', 'Exact times take "at": "at 5 PM".');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'In', 0, 0), (@q, 'On', 0, 1), (@q, 'At', 1, 2), (@q, 'Under', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'Fill in the blank: "He goes to school ___ bus."', 'We travel "by" bus, car, train...');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'on', 0, 0), (@q, 'in', 0, 1), (@q, 'by', 1, 2), (@q, 'with', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What is the past tense of "run"?', '"Run" is irregular: run - ran.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'Ran', 1, 0), (@q, 'Runned', 0, 1), (@q, 'Running', 0, 2), (@q, 'Runs', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What is the past tense of "eat"?', '"Eat" is irregular: eat - ate.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'Eated', 0, 0), (@q, 'Ate', 1, 1), (@q, 'Eaten', 0, 2), (@q, 'Eating', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'Which sentence is correct?', 'Plural nouns take "are": "There are five cats."');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, '"There is five cats."', 0, 0), (@q, '"There are five cats."', 1, 1), (@q, '"There am five cats."', 0, 2), (@q, '"There be five cats."', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What is the opposite of "noisy"?', '"Quiet" is the opposite of "noisy".');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'Loud', 0, 0), (@q, 'Quiet', 1, 1), (@q, 'Bright', 0, 2), (@q, 'Fast', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What is the opposite of "happy"?', '"Sad" is the opposite of "happy".');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'Glad', 0, 0), (@q, 'Sad', 1, 1), (@q, 'Angry', 0, 2), (@q, 'Fast', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'Which article is used before a singular consonant word (like "cat") for the first time?', 'Use "a" before consonant sounds: "a cat".');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'An', 0, 0), (@q, 'A', 1, 1), (@q, 'The only', 0, 2), (@q, 'None', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'When do we use "the"?', '"The" points to something specific or already mentioned.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'For any random thing', 0, 0), (@q, 'When talking about something specific or already mentioned', 1, 1), (@q, 'Only for names', 0, 2), (@q, 'Never', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What is the short form of "is not"?', 'is not = isn''t.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'Aren''t', 0, 0), (@q, 'Isn''t', 1, 1), (@q, 'Wasn''t', 0, 2), (@q, 'Don''t', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What is the short form of "are not"?', 'are not = aren''t.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'Isn''t', 0, 0), (@q, 'Aren''t', 1, 1), (@q, 'Weren''t', 0, 2), (@q, 'Don''t', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'Find the adjective in: "The clever boy solved it."', '"Clever" describes the boy.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'Boy', 0, 0), (@q, 'Solved', 0, 1), (@q, 'Clever', 1, 2), (@q, 'It', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'Find the adverb in: "She sings beautifully."', '"Beautifully" describes how she sings.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'She', 0, 0), (@q, 'Sings', 0, 1), (@q, 'Beautifully', 1, 2), (@q, 'None', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'Which question word asks about a person?', '"Who" asks about people.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'Where', 0, 0), (@q, 'When', 0, 1), (@q, 'Who', 1, 2), (@q, 'Why', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'Which question word asks about a place?', '"Where" asks about places.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'Who', 0, 0), (@q, 'Where', 1, 1), (@q, 'What time', 0, 2), (@q, 'How many', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'Fill in the blank: "This is my brother. ___ name is Tom."', 'Brother is male singular, so use "his".');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'Her', 0, 0), (@q, 'His', 1, 1), (@q, 'Their', 0, 2), (@q, 'Your', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'Fill in the blank: "We love ___ school."', '"Our" shows possession for "we".');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'our', 1, 0), (@q, 'us', 0, 1), (@q, 'we', 0, 2), (@q, 'ours', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What tense do we use for tomorrow''s plans?', 'Future Simple (will) is used for future plans.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'Past Simple', 0, 0), (@q, 'Present Simple', 0, 1), (@q, 'Future Simple (will)', 1, 2), (@q, 'Present Perfect', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'Complete: "Tomorrow, I ___ visit my friend."', '"Tomorrow" signals future: use "will".');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'did', 0, 0), (@q, 'will', 1, 1), (@q, 'was', 0, 2), (@q, 'am', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What is the base form of "went"?', 'went is the past of "go".');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'Go', 1, 0), (@q, 'Gone', 0, 1), (@q, 'Going', 0, 2), (@q, 'Goes', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What is the base form of "saw"?', 'saw is the past of "see".');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'Seen', 0, 0), (@q, 'See', 1, 1), (@q, 'Seeing', 0, 2), (@q, 'Sees', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'Which conjunction means "plus / in addition"?', '"And" adds things together.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'But', 0, 0), (@q, 'And', 1, 1), (@q, 'Or', 0, 2), (@q, 'So', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'Which sentence has correct capital letters?', 'Sentences start with a capital and names are capitalized: "My name is John."');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, '"my name is john."', 0, 0), (@q, '"My name is John."', 1, 1), (@q, '"my Name is john."', 0, 2), (@q, '"MY NAME IS JOHN."', 0, 3);
