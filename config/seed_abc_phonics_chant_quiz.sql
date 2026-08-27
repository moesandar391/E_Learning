-- =====================================================================
-- Seed script: ABC Phonics Chant Module Quiz (30 questions)
-- Run once in phpMyAdmin or MySQL CLI.
--   SET @module_id = 16;  (16 = ABC Phonics Chant | Sounds and Actions)
-- =====================================================================

SET @module_id = 16;  -- <-- ABC Phonics Chant module

INSERT INTO quizzes (module_id, quiz_title, passing_score, question_limit, time_limit, random_questions, random_answers)
VALUES (@module_id, 'ABC Phonics Chant Module Quiz', 70, 10, 0, 1, 1);

SET @quiz_id = LAST_INSERT_ID();

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What is phonics?', 'A method of teaching reading and spelling by connecting letters to sounds.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'A method of teaching reading and spelling by connecting letters to sounds', 1, 0), (@q, 'Telephone communication', 0, 1), (@q, 'Memorizing books without looking', 0, 2), (@q, 'Calligraphy writing', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What is the short sound for the letter ''A''?', '/ae/ as in apple.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, '/ei/ as in ape', 0, 0), (@q, '/ae/ as in apple', 1, 1), (@q, '/ah/ as in car', 0, 2), (@q, '/aw/ as in ball', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What sound does the letter ''B'' make?', '/b/ as in bat.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, '/p/ as in pan', 0, 0), (@q, '/b/ as in bat', 1, 1), (@q, '/d/ as in dog', 0, 2), (@q, '/v/ as in van', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What is a phoneme?', 'The smallest unit of sound in spoken language.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'A telephone device', 0, 0), (@q, 'The smallest unit of sound in spoken language', 1, 1), (@q, 'A written letter', 0, 2), (@q, 'A punctuation mark', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What is a grapheme?', 'A written letter symbol that represents a sound.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'A written letter symbol that represents a sound', 1, 0), (@q, 'A spoken sound with no spelling', 0, 1), (@q, 'A grammar rule', 0, 2), (@q, 'A paragraph structure', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What sound does a "hard C" make (like in "cat")?', 'The /k/ sound.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, '/s/ sound', 0, 0), (@q, '/k/ sound', 1, 1), (@q, '/sh/ sound', 0, 2), (@q, '/ch/ sound', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What sound does a "soft C" make (like in "city")?', 'The /s/ sound.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, '/k/ sound', 0, 0), (@q, '/s/ sound', 1, 1), (@q, '/g/ sound', 0, 2), (@q, '/z/ sound', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What is "blending" in phonics?', 'Putting individual letter sounds together to read a word (e.g., /c/-/a/-/t/ = cat).');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'Breaking a word apart', 0, 0), (@q, 'Putting individual letter sounds together to read a word (e.g., /c/-/a/-/t/ = cat)', 1, 1), (@q, 'Mixing paint colors', 0, 2), (@q, 'Forgetting sounds', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What is "segmenting" in phonics?', 'Breaking a word down into its individual sounds for spelling (e.g., dog = /d/-/o/-/g/).');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'Combining sounds', 0, 0), (@q, 'Breaking a word down into its individual sounds for spelling (e.g., dog = /d/-/o/-/g/)', 1, 1), (@q, 'Reading fast', 0, 2), (@q, 'Writing without spaces', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What sound does the digraph ''sh'' make?', '/sh/ as in ship.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, '/ch/ as in chair', 0, 0), (@q, '/sh/ as in ship', 1, 1), (@q, '/th/ as in thumb', 0, 2), (@q, '/f/ as in phone', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What sound does the digraph ''ch'' make?', '/ch/ as in chair.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, '/sh/ as in ship', 0, 0), (@q, '/ch/ as in chair', 1, 1), (@q, '/k/ as in kite', 0, 2), (@q, '/s/ as in sun', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What sound does the digraph ''th'' make?', '/th/ as in thumb or this.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, '/th/ as in thumb or this', 1, 0), (@q, '/t/ as in top', 0, 1), (@q, '/h/ as in hat', 0, 2), (@q, '/b/ as in boy', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What sound does the digraph ''ph'' make?', '/f/ as in phone.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, '/p/ as in pen', 0, 0), (@q, '/h/ as in hat', 0, 1), (@q, '/f/ as in phone', 1, 2), (@q, '/v/ as in van', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What is an example of a short vowel sound for ''E''?', '/e/ as in egg.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, '/ee/ as in eagle', 0, 0), (@q, '/e/ as in egg', 1, 1), (@q, '/ee/ as in eat', 0, 2), (@q, '/igh/ as in ice', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What is an example of a short vowel sound for ''I''?', '/i/ as in igloo.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, '/igh/ as in ice', 0, 0), (@q, '/i/ as in igloo', 1, 1), (@q, '/ee/ as in machine', 0, 2), (@q, '/oo/ as in glue', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What is an example of a short vowel sound for ''O''?', '/o/ as in octopus.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, '/oh/ as in open', 0, 0), (@q, '/o/ as in octopus', 1, 1), (@q, '/oo/ as in pool', 0, 2), (@q, '/oh/ as in oak', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What is an example of a short vowel sound for ''U''?', '/u/ as in umbrella.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, '/you/ as in unicorn', 0, 0), (@q, '/oo/ as in flute', 0, 1), (@q, '/u/ as in umbrella', 1, 2), (@q, '/oh/ as in urn', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What does CVC stand for?', 'Consonant-Vowel-Consonant (e.g., hat, pen, dog).');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'Consonant-Vowel-Consonant (e.g., hat, pen, dog)', 1, 0), (@q, 'Vowel-Vowel-Vowel', 0, 1), (@q, 'Consonant-Consonant-Consonant', 0, 2), (@q, 'No vowels', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'Which of these is a CVC word?', '"Pig" follows consonant-vowel-consonant.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'Tree', 0, 0), (@q, 'Pig', 1, 1), (@q, 'Play', 0, 2), (@q, 'Boat', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'Why do phonics chants use physical actions (movement)?', 'To help memory by linking body movements to letter sounds.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'To make students tired', 0, 0), (@q, 'To help memory by linking body movements to letter sounds', 1, 1), (@q, 'To distract children', 0, 2), (@q, 'To replace books', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What does the magic ''E'' (silent ''E'') do to a short vowel?', 'Makes the vowel long (e.g., cap becomes cape).');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'Deletes it', 0, 0), (@q, 'Makes the vowel long (e.g., cap becomes cape)', 1, 1), (@q, 'Turns it into a consonant', 0, 2), (@q, 'Makes it plural', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What sound does the letter ''Z'' make?', '/z/ as in zebra.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, '/s/ as in sun', 0, 0), (@q, '/z/ as in zebra', 1, 1), (@q, '/y/ as in yes', 0, 2), (@q, '/sh/ as in shop', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What sound does the letter ''S'' make at the end of "cats"?', 'The /s/ sound.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, '/z/ sound', 0, 0), (@q, '/s/ sound', 1, 1), (@q, '/iz/ sound', 0, 2), (@q, 'No sound', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What is a consonant blend (cluster)?', 'Two or three consonants where each keeps its own sound (e.g., "bl" in blue).');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'Two vowels stuck together', 0, 0), (@q, 'Two or three consonants where each keeps its own sound (e.g., "bl" in blue)', 1, 1), (@q, 'A word with no consonants', 0, 2), (@q, 'A punctuation error', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What sound does the letter ''W'' make?', '/w/ as in water.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, '/v/ as in van', 0, 0), (@q, '/w/ as in water', 1, 1), (@q, '/u/ as in up', 0, 2), (@q, '/m/ as in man', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What is an alphabet chant?', 'A fun rhythm or song linking letters, keywords, and sounds to remember phonics easily.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'A scary poem', 0, 0), (@q, 'A fun rhythm or song linking letters, keywords, and sounds to remember phonics easily', 1, 1), (@q, 'A hard spelling test', 0, 2), (@q, 'An adult speech', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What sound does ''Y'' make at the start of "yellow"?', 'The /y/ sound as in yes.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, '/igh/ as in fly', 0, 0), (@q, '/y/ sound as in yes', 1, 1), (@q, '/ee/ as in baby', 0, 2), (@q, '/w/ as in water', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What sound does ''ng'' make at the end of "sing"?', 'The /ng/ nasal back sound.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, '/n/ sound', 0, 0), (@q, '/g/ sound', 0, 1), (@q, '/ng/ (nasal back sound)', 1, 2), (@q, '/d/ sound', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What is a "sight word"?', 'A word kids learn to recognize instantly without sounding it out (e.g., "the").');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'A word kids learn to recognize instantly without sounding it out (e.g., "the")', 1, 0), (@q, 'A word read with glasses', 0, 1), (@q, 'A word with a picture', 0, 2), (@q, 'A long scientific term', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'Why are actions and chants effective for learners?', 'They engage multiple senses (seeing, hearing, moving), making it easy to remember.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'They engage multiple senses (seeing, hearing, moving), making it easy to remember', 1, 0), (@q, 'They need no teacher', 0, 1), (@q, 'They make kids run fast', 0, 2), (@q, 'They stop talking', 0, 3);
