-- =====================================================================
-- Seed script: Listening Module Quiz (30 questions)
-- Run once in phpMyAdmin or MySQL CLI.
--   SET @module_id = 14;  (14 = Listening)
-- =====================================================================

SET @module_id = 14;  -- <-- Listening module

INSERT INTO quizzes (module_id, quiz_title, passing_score, question_limit, time_limit, random_questions, random_answers)
VALUES (@module_id, 'Listening Module Quiz', 70, 10, 0, 1, 1);

SET @quiz_id = LAST_INSERT_ID();

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What is the difference between hearing and listening?', 'Hearing is physical; listening is active understanding.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'Hearing is physical; listening is active understanding.', 1, 0), (@q, 'Hearing needs headphones.', 0, 1), (@q, 'There is no difference.', 0, 2), (@q, 'Listening is automatic; hearing takes effort.', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What does "listening for gist" mean?', 'Understanding the general idea without worrying about small details.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'Listening for every single letter', 0, 0), (@q, 'Listening to understand the general idea without worrying about small details', 1, 1), (@q, 'Writing down every word', 0, 2), (@q, 'Memorizing numbers', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What does "listening for specific details" mean?', 'Listening closely for exact facts, names, or numbers.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'Ignoring the speaker', 0, 0), (@q, 'Listening closely for exact facts, names, or numbers', 1, 1), (@q, 'Sleeping during audio', 0, 2), (@q, 'Guessing moods', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What is inferencing in listening?', 'Figuring out hidden or unstated meanings from tone and context.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'Saying words backward', 0, 0), (@q, 'Figuring out hidden or unstated meanings from tone and context', 1, 1), (@q, 'Translating every word', 0, 2), (@q, 'Reading the script', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'Why are cue words (like "First" or "In conclusion") helpful?', 'They help you follow the structure and organization of the talk.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'They make the speaker talk faster', 0, 0), (@q, 'They help you follow the structure and organization of the talk', 1, 1), (@q, 'They are insults', 0, 2), (@q, 'They end the class', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What is active listening?', 'Fully concentrating, understanding, and remembering what is said.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'Daydreaming', 0, 0), (@q, 'Fully concentrating, understanding, and remembering what is said', 1, 1), (@q, 'Running with earphones', 0, 2), (@q, 'Recording audio', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What should you do before listening to an audio track?', 'Read the questions first to know what to expect.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'Cover your ears', 0, 0), (@q, 'Read the questions first to know what to expect', 1, 1), (@q, 'Turn off the player', 0, 2), (@q, 'Leave the room', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What does a speaker''s tone of voice show?', 'Their feelings and attitude (e.g., happy, angry, sad).');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'Their shoe size', 0, 0), (@q, 'Their feelings and attitude (e.g., happy, angry, sad)', 1, 1), (@q, 'The speed of sound', 0, 2), (@q, 'The date', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'How does taking quick notes help during a listening test?', 'It captures key words so you don''t forget them.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'It distracts you', 0, 0), (@q, 'It captures key words so you don''t forget them', 1, 1), (@q, 'It slows down the audio', 0, 2), (@q, 'It wastes paper', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What is a common distractor in listening tests?', 'Words that sound similar but mean something else.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'Mentioning words that sound similar but mean something else', 1, 0), (@q, 'Total silence', 0, 1), (@q, 'Speaking another language', 0, 2), (@q, 'Turning off the mic', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What does "predicting" mean in listening?', 'Guessing what the speaker will say next using clues.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'Guessing what the speaker will say next using clues', 1, 0), (@q, 'Predicting the lottery', 0, 1), (@q, 'Knowing test questions ahead of time', 0, 2), (@q, 'Memorizing a script', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'If a speaker says, "On the other hand...", what comes next?', 'A contrasting or opposite idea.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'Agreement', 0, 0), (@q, 'A contrasting or opposite idea', 1, 1), (@q, 'A secret', 0, 2), (@q, 'A math formula', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What does a rising pitch at the end of a sentence usually mean?', 'A question.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'A statement', 0, 0), (@q, 'A question', 1, 1), (@q, 'Anger', 0, 2), (@q, 'Boredom', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What is a dialogue?', 'A conversation between two or more people.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'A speech by one person', 0, 0), (@q, 'A conversation between two or more people', 1, 1), (@q, 'A book chapter', 0, 2), (@q, 'A silent movie', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What is a monologue?', 'A long speech by one single speaker.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'A conversation of five people', 0, 0), (@q, 'A long speech by one single speaker', 1, 1), (@q, 'A concert', 0, 2), (@q, 'A phone static sound', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'If someone says, "Take the second turn on your left," what are they giving?', 'Directions / location.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'A recipe', 0, 0), (@q, 'Directions / location', 1, 1), (@q, 'Clothing advice', 0, 2), (@q, 'Weather info', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What is background noise in a recording?', 'Extra sounds like traffic or cafe chat that show the setting.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'Extra sounds like traffic or cafe chat that show the setting', 1, 0), (@q, 'A broken microphone', 0, 1), (@q, 'The main voice', 0, 2), (@q, 'Subtitles', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What should you do if you miss a word while listening?', 'Let it go immediately and keep listening to the next part.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'Panic and stop', 0, 0), (@q, 'Let it go immediately and keep listening to the next part', 1, 1), (@q, 'Rewind live radio', 0, 2), (@q, 'Shout at the speaker', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What is an accent?', 'A special way of pronouncing words tied to a region or country.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'A spelling mistake', 0, 0), (@q, 'A special way of pronouncing words tied to a region or country', 1, 1), (@q, 'Loud volume', 0, 2), (@q, 'Fast talking', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'Which signal word shows cause and effect?', '"As a result" shows cause and effect.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'Meanwhile', 0, 0), (@q, 'As a result', 1, 1), (@q, 'Secondly', 0, 2), (@q, 'Far away', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'Why do speakers use rhetorical questions (questions that don''t need answers)?', 'To engage the audience and make them think.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'To engage the audience and make them think', 1, 0), (@q, 'To test if people can speak', 0, 1), (@q, 'To confuse people', 0, 2), (@q, 'To end early', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'If a speaker talks louder, what are they probably doing?', 'Emphasizing an important point.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'Telling a joke', 0, 0), (@q, 'Emphasizing an important point', 1, 1), (@q, 'Leaving the room', 0, 2), (@q, 'Nothing', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What is intensive listening?', 'Listening closely for every single sound and grammar detail.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'Listening closely for every single sound and grammar detail', 1, 0), (@q, 'Sleeping while listening', 0, 1), (@q, 'Background party music', 0, 2), (@q, 'Driving podcasts', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What is extensive listening?', 'Listening to long audio (like podcasts or stories) for general pleasure and practice.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'Listening to long audio (like podcasts or stories) for general pleasure and practice', 1, 0), (@q, 'Analyzing every syllable in a 2-second clip', 0, 1), (@q, 'Taking dictation tests', 0, 2), (@q, 'Memorizing speeches', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What do phrases like "To sum up" tell the listener?', 'The speaker is finishing their talk.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'A new topic is starting', 0, 0), (@q, 'The speaker is finishing their talk', 1, 1), (@q, 'An argument is beginning', 0, 2), (@q, 'The audio is broken', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What does "context" mean in listening?', 'The situation or setting where communication happens.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'A dictionary meaning', 0, 0), (@q, 'The situation or setting where communication happens', 1, 1), (@q, 'Audio speed', 0, 2), (@q, 'Test creator''s name', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'Why do listening tests often include numbers and dates?', 'They test your ability to catch precise details accurately.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'They are easy to guess', 0, 0), (@q, 'They test your ability to catch precise details accurately', 1, 1), (@q, 'They don''t matter', 0, 2), (@q, 'They make tests longer', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What is paraphrasing in listening?', 'Restating the speaker''s ideas in different words.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'Copying word-for-word', 0, 0), (@q, 'Restating the speaker''s ideas in different words', 1, 1), (@q, 'Translating to code', 0, 2), (@q, 'Whispering', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'Which phrase shows agreement?', '"That''s right" or "Exactly."');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, '"I completely disagree."', 0, 0), (@q, '"That''s right" or "Exactly."', 1, 1), (@q, '"That makes no sense."', 0, 2), (@q, '"Silence."', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What is a big barrier to good listening?', 'Distractions and lack of focus.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'Clear voice', 0, 0), (@q, 'Distractions and lack of focus', 1, 1), (@q, 'Useful notes', 0, 2), (@q, 'Paying attention', 0, 3);
