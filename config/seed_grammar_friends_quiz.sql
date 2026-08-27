-- =====================================================================
-- Seed script: Grammar Friends Module Quiz (30 questions)
-- Run once in phpMyAdmin or MySQL CLI.
--   SET @module_id = 8;  (8 = Grammar Friends)
-- =====================================================================

SET @module_id = 8;  -- <-- Grammar Friends module

-- 1. Create the quiz (randomized questions per attempt)
INSERT INTO quizzes (module_id, quiz_title, passing_score, question_limit, time_limit, random_questions, random_answers)
VALUES (@module_id, 'Grammar Friends Module Quiz', 70, 10, 0, 1, 1);

SET @quiz_id = LAST_INSERT_ID();

-- =====================================================================
-- Questions
-- =====================================================================
INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What are "countable nouns"?', 'Countable nouns can be counted, like apple and apples.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'Nouns you cannot count', 0, 0), (@q, 'Nouns you can count (like apple and apples)', 1, 1), (@q, 'Nouns that are only liquids', 0, 2), (@q, 'Nouns that are ideas', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What are "uncountable nouns"?', 'Uncountable nouns cannot be counted individually, like water or rice.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'Nouns you can count one by one', 0, 0), (@q, 'Nouns you cannot count individually (like water or rice)', 1, 1), (@q, 'Nouns that are people', 0, 2), (@q, 'Nouns ending in ''s''', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'When do we use "some"?', '"Some" is used in affirmative sentences and offers, like "some water".');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'In negative sentences', 0, 0), (@q, 'In affirmative sentences and offers (like "some water")', 1, 1), (@q, 'Only in questions', 0, 2), (@q, 'Never', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'When do we usually use "any"?', '"Any" is used in negative sentences and questions.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'In positive sentences', 0, 0), (@q, 'In negative sentences and questions', 1, 1), (@q, 'Only with singular nouns', 0, 2), (@q, 'To show excitement', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'Fill in the blank: "Is there ___ juice left?"', 'Questions use "any".');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'some', 0, 0), (@q, 'any', 1, 1), (@q, 'a', 0, 2), (@q, 'many', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What is the past tense of "have"?', 'The past tense of "have" is "had".');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'Has', 0, 0), (@q, 'Had', 1, 1), (@q, 'Having', 0, 2), (@q, 'Haves', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'Which modal verb means you are NOT allowed to do something?', '"Mustn''t (must not)" means it is forbidden.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'Should', 0, 0), (@q, 'Mustn''t (must not)', 1, 1), (@q, 'Can', 0, 2), (@q, 'Might', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What does "should" express?', '"Should" gives good advice.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'A strict order', 0, 0), (@q, 'Good advice', 1, 1), (@q, 'Past time', 0, 2), (@q, 'Impossible things', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'Which question word asks about time?', '"When" (or "What time") asks about time.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'Where', 0, 0), (@q, 'When (or What time)', 1, 1), (@q, 'Who', 0, 2), (@q, 'Why', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'Which question word asks for a reason?', '"Why" asks for a reason.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'When', 0, 0), (@q, 'Why', 1, 1), (@q, 'How many', 0, 2), (@q, 'What', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What is the comparative form of "good"?', '"Good" becomes "better".');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'Gooder', 0, 0), (@q, 'Better', 1, 1), (@q, 'Best', 0, 2), (@q, 'More good', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What is the superlative form of "good"?', '"Good" becomes "best".');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'Better', 0, 0), (@q, 'Best', 1, 1), (@q, 'Goodest', 0, 2), (@q, 'Most good', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What is the comparative form of "bad"?', '"Bad" becomes "worse".');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'Badder', 0, 0), (@q, 'Worse', 1, 1), (@q, 'Worst', 0, 2), (@q, 'More bad', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'Fill in the blank: "This pen is mine. It belongs to ___." ', '"Mine" already shows ownership; after "to" use "me".');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'me', 1, 0), (@q, 'my', 0, 1), (@q, 'I', 0, 2), (@q, 'mine', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'Which adverb of frequency means 100% of the time?', '"Always" means 100% of the time.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'Sometimes', 0, 0), (@q, 'Never', 0, 1), (@q, 'Always', 1, 2), (@q, 'Usually', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'Where does an adverb of frequency usually go in a normal sentence?', 'Before the main verb, e.g., "I always sleep".');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'Before the main verb (e.g., "I always sleep")', 1, 0), (@q, 'At the very end always', 0, 1), (@q, 'Before the subject', 0, 2), (@q, 'Outside the sentence', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What is the -ing form of "run"?', 'Double the consonant: "running".');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'Runing', 0, 0), (@q, 'Running', 1, 1), (@q, 'Runeing', 0, 2), (@q, 'Runs', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What is the -ing form of "write"?', 'Drop the silent e: "writing".');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'Writeing', 0, 0), (@q, 'Writing', 1, 1), (@q, 'Writng', 0, 2), (@q, 'Writin', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'Which tense shows an action happening right now?', 'Present Continuous shows actions happening now.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'Present Simple', 0, 0), (@q, 'Present Continuous', 1, 1), (@q, 'Past Simple', 0, 2), (@q, 'Future Simple', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'Complete: "Look! The cat ___. "', '"Look!" signals now, so use "is sleeping".');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'sleep', 0, 0), (@q, 'sleeps', 0, 1), (@q, 'is sleeping', 1, 2), (@q, 'slept', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What is the past tense of "see"?', 'The past tense of "see" is "saw".');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'Seed', 0, 0), (@q, 'Saw', 1, 1), (@q, 'Seen', 0, 2), (@q, 'Seeing', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What is the past participle of "eat"?', 'eat - ate - eaten.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'Ate', 0, 0), (@q, 'Eated', 0, 1), (@q, 'Eaten', 1, 2), (@q, 'Eating', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'Which phrase compares two things that are equal?', '"As... as" compares equal things, e.g., "as tall as".');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'More... than', 0, 0), (@q, 'As... as (e.g., "as tall as")', 1, 1), (@q, 'The...est', 0, 2), (@q, 'Less... than', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What is the opposite of "friendly"?', 'Add the prefix un-: "unfriendly".');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'Unfriendly', 1, 0), (@q, 'Friendless', 0, 1), (@q, 'Misfriendly', 0, 2), (@q, 'Nonfriendly', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'Make an adverb from the adjective "quick":', 'Add -ly: "quickly".');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'Quicker', 0, 0), (@q, 'Quickly', 1, 1), (@q, 'Quickness', 0, 2), (@q, 'Quick', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What is the plural form of "fish"?', 'Some nouns stay the same: fish -> fish.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'Fish', 1, 0), (@q, 'Fishs', 0, 1), (@q, 'Fishies', 0, 2), (@q, 'Fishes', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'Which pronoun includes yourself and others?', '"We" includes yourself and others.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'They', 0, 0), (@q, 'He', 0, 1), (@q, 'We', 1, 2), (@q, 'You', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'Fill in the blank: "My birthday is ___ June."', 'Months take "in": "in June".');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'on', 0, 0), (@q, 'at', 0, 1), (@q, 'in', 1, 2), (@q, 'by', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What is the negative word for Past Simple?', 'Past Simple negatives use "didn''t".');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'Don''t', 0, 0), (@q, 'Doesn''t', 0, 1), (@q, 'Didn''t', 1, 2), (@q, 'Aren''t', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'Choose the correct sentence:', 'After doesn''t, use the base verb: "She doesn''t like apples."');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, '"She doesn''t like apples."', 1, 0), (@q, '"She don''t like apples."', 0, 1), (@q, '"She doesn''t likes apples."', 0, 2), (@q, '"She not like apples."', 0, 3);
