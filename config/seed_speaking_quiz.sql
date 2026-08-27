-- =====================================================================
-- Seed script: Speaking Module Quiz (30 questions)
-- Run once in phpMyAdmin or MySQL CLI.
--   SET @module_id = 1;  (1 = Speaking)
-- =====================================================================

SET @module_id = 1;  -- <-- Speaking module

-- 1. Create the quiz (randomized questions per attempt)
INSERT INTO quizzes (module_id, quiz_title, passing_score, question_limit, time_limit, random_questions, random_answers)
VALUES (@module_id, 'Speaking Module Quiz', 70, 10, 0, 1, 1);

SET @quiz_id = LAST_INSERT_ID();

-- =====================================================================
-- Questions
-- =====================================================================
INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What do we use words like "um" or "well" for when speaking?', 'Filler words like "um" or "well" give you a moment to think while keeping your turn.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'To stop talking forever', 0, 0), (@q, 'To think for a second while keeping our turn', 1, 1), (@q, 'To get angry', 0, 2), (@q, 'To sing a song', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What is a polite way to interrupt someone in class?', '"Excuse me, can I say something?" politely asks for a turn to speak.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, '"Shut up!"', 0, 0), (@q, '"Excuse me, can I say something?"', 1, 1), (@q, '"You are wrong."', 0, 2), (@q, '"Stop talking now."', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What does "intonation" mean?', 'Intonation is the rise and fall of your voice when speaking.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'How fast you run', 0, 0), (@q, 'The rise and fall of your voice when speaking', 1, 1), (@q, 'How loud the TV is', 0, 2), (@q, 'The clothes you wear', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'How do you greet a teacher for the first time formally?', '"Hello, nice to meet you." is a polite, formal greeting.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, '''What''s up, bro?''', 0, 0), (@q, '"Hello, nice to meet you."', 1, 1), (@q, '"Yo!"', 0, 2), (@q, '"See ya."', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'Where is the stress in the word "photograph"?', 'In "photograph", the stress is on the first syllable: PHO-to-graph.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'PHO-to-graph (first part)', 1, 0), (@q, 'pho-TO-graph (middle)', 0, 1), (@q, 'photo-GRAPH (end)', 0, 2), (@q, 'All parts are equal', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'How do you reply when someone says, "Thank you for your help"?', '"You''re welcome." is the polite reply to thanks.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, '"I don''t care."', 0, 0), (@q, '"You''re welcome."', 1, 1), (@q, '"Go away."', 0, 2), (@q, '"No."', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What do you say if you didn''t understand your friend?', '"Could you please say that again?" politely asks for repetition.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, '"You make no sense."', 0, 0), (@q, '"Could you please say that again?"', 1, 1), (@q, '"Speak to someone else."', 0, 2), (@q, '"I hate this."', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'Which phrase helps you change the topic?', '"By the way..." is commonly used to change the topic.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, '"By the way..."', 1, 0), (@q, '"Stop right there."', 0, 1), (@q, '"The end."', 0, 2), (@q, '"Go to sleep."', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What kind of tone makes a presentation fun and interesting?', 'An excited and clear tone keeps listeners engaged.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'Bored and sleepy', 0, 0), (@q, 'Excited and clear', 1, 1), (@q, 'Very angry', 0, 2), (@q, 'Whispering quietly', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'Which sentence usually goes up at the end (rising tone)?', 'Yes/No questions like "Are you coming?" usually rise at the end.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, '"I like apples."', 0, 0), (@q, '"Are you coming?"', 1, 1), (@q, '"Close the door."', 0, 2), (@q, '"My name is Tom."', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'How can you stop feeling nervous before speaking?', 'Deep breaths and practice help calm nerves.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'Run away', 0, 0), (@q, 'Take deep breaths and practice', 1, 1), (@q, 'Close your eyes and sleep', 0, 2), (@q, 'Eat a lot of food', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'How do you politely say you disagree with a friend?', '"I see it a bit differently..." expresses disagreement politely.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, '"Your idea is stupid."', 0, 0), (@q, '"I see it a bit differently..."', 1, 1), (@q, '"You are completely wrong!"', 0, 2), (@q, '"Shut up."', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What does looking someone in the eyes show?', 'Eye contact shows confidence and honesty.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'Fear', 0, 0), (@q, 'Confidence and honesty', 1, 1), (@q, 'Anger', 0, 2), (@q, 'Boredom', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'How do you end a phone call nicely?', '"Bye! Have a nice day." is a friendly way to end a call.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, '"Bye! Have a nice day."', 1, 0), (@q, '"Hurry up and hang up."', 0, 1), (@q, '"Don''t call me ever."', 0, 2), (@q, '"Whatever."', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'Why do we stress certain words in English?', 'Stress makes important words stand out.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'To sing better', 0, 0), (@q, 'To make important words stand out', 1, 1), (@q, 'To confuse people', 0, 2), (@q, 'To speak quietly', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'How do you ask a friend for their opinion?', '"What do you think about this?" directly asks for an opinion.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, '"What do you think about this?"', 1, 0), (@q, '"Don''t say anything."', 0, 1), (@q, '"You hate this, right?"', 0, 2), (@q, '"Listen to me only."', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What does a falling tone at the end of a sentence usually mean?', 'A falling tone usually signals the sentence is finished.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'A question', 0, 0), (@q, 'The sentence is finished', 1, 1), (@q, 'Extreme shock', 0, 2), (@q, 'Confusion', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What word means speaking clearly so everyone hears?', 'Articulation means clear speaking.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'Mumble', 0, 0), (@q, 'Articulation (clear speaking)', 1, 1), (@q, 'Whisper', 0, 2), (@q, 'Shout', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'How do you answer someone who says your drawing is nice?', '"Thank you so much!" is the polite reply to a compliment.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, '"No, it''s ugly."', 0, 0), (@q, '"Thank you so much!"', 1, 1), (@q, '"You''re lying to me."', 0, 2), (@q, '"I know."', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What is a good way to start talking to a new student?', 'A friendly comment like "Hi, nice weather today, right?" starts a conversation.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, '"Give me your lunch."', 0, 0), (@q, '"Hi, nice weather today, right?"', 1, 1), (@q, '"Why are you looking at me?"', 0, 2), (@q, '"Tell me your secrets."', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'How do you ask a classmate to help you?', '"Could you help me with this?" is a polite request for help.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, '"Do my homework now."', 0, 0), (@q, '"Could you help me with this?"', 1, 1), (@q, '"You must help me."', 0, 2), (@q, '"Help me or else."', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What do we call special words used in a specific job or hobby?', 'Jargon refers to special words used in a specific job or hobby.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'Slang', 0, 0), (@q, 'Jargon (special words)', 1, 1), (@q, 'Gossip', 0, 2), (@q, 'Noise', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'How do you show someone you are listening to them?', 'Saying "I see" or "Right" shows active listening.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'Look at your phone', 0, 0), (@q, 'Say "I see" or "Right"', 1, 1), (@q, 'Walk away', 0, 2), (@q, 'Yawn loudly', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'Which sentence shows you are not sure about something?', '"I''m not totally sure, but maybe." expresses uncertainty.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, '"I know this for sure."', 0, 0), (@q, '"I''m not totally sure, but maybe."', 1, 1), (@q, '"This is 100% wrong."', 0, 2), (@q, '"Water is wet."', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What is an "impromptu" speech?', 'An impromptu speech is given with no practice or planning.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'A speech you prepared for 1 year', 0, 0), (@q, 'A speech you give with no practice or planning', 1, 1), (@q, 'A recorded video', 0, 2), (@q, 'A song', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'How do you say a very formal "thank you"?', '"I am very grateful for your help." is very formal.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, '"Thx."', 0, 0), (@q, '"I am very grateful for your help."', 1, 1), (@q, '"Cool, thanks."', 0, 2), (@q, '"Yeah, okay."', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'How do you check if your friend understands your explanation?', '"Does that make sense?" politely checks understanding.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, '"Are you dumb?"', 0, 0), (@q, '"Does that make sense?"', 1, 1), (@q, '"You won''t get it."', 0, 2), (@q, '"Forget it."', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'Why do we control our speed when talking?', 'Controlling speed helps people understand easily.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'To finish fast', 0, 0), (@q, 'So people can understand us easily', 1, 1), (@q, 'To fall asleep', 0, 2), (@q, 'To sound like a robot', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What do you say when finishing a presentation?', '"To wrap things up..." signals the conclusion of a presentation.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, '"To wrap things up..."', 1, 0), (@q, '"Let''s start now."', 0, 1), (@q, '"Goodbye forever."', 0, 2), (@q, '"I''m done, bye."', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What is the best way to invite a friend to speak in a group?', '"What do you think, Alex?" invites someone to share their ideas.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, '"Be quiet."', 0, 0), (@q, '"What do you think, Alex?"', 1, 1), (@q, '"Don''t talk."', 0, 2), (@q, '"Listen to me."', 0, 3);
