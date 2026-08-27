-- =====================================================================
-- Seed script: Great Writing (Foundation) Module Quiz (30 questions)
-- Run once in phpMyAdmin or MySQL CLI.
--   SET @module_id = 2;  (2 = Great Writing Foundation)
-- =====================================================================

SET @module_id = 2;  -- <-- Great Writing (Foundation) module

-- 1. Create the quiz (randomized questions per attempt)
INSERT INTO quizzes (module_id, quiz_title, passing_score, question_limit, time_limit, random_questions, random_answers)
VALUES (@module_id, 'Great Writing (Foundation) Module Quiz', 70, 10, 0, 1, 1);

SET @quiz_id = LAST_INSERT_ID();

-- =====================================================================
-- Questions
-- =====================================================================
INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What is a paragraph?', 'A paragraph is a group of sentences about one main idea.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'A single word', 0, 0), (@q, 'A group of sentences about one main idea', 1, 1), (@q, 'A whole book', 0, 2), (@q, 'A picture', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What are the three main parts of a paragraph?', 'A paragraph has a topic sentence, supporting sentences, and a concluding sentence.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'Beginning, middle, end', 0, 0), (@q, 'Topic sentence, supporting sentences, concluding sentence', 1, 1), (@q, 'Title, picture, page number', 0, 2), (@q, 'Noun, verb, adjective', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What does the topic sentence do?', 'The topic sentence tells the main idea of the paragraph.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'Tells the main idea of the paragraph', 1, 0), (@q, 'Ends the story', 0, 1), (@q, 'Gives a random fact', 0, 2), (@q, 'Asks a trick question', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'Where is the topic sentence usually found?', 'The topic sentence usually comes at the very beginning of the paragraph.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'At the very beginning of the paragraph', 1, 0), (@q, 'At the very end of the book', 0, 1), (@q, 'In the middle of nowhere', 0, 2), (@q, 'On another page', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What do supporting sentences do?', 'Supporting sentences give details and examples to explain the topic sentence.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'Change the subject', 0, 0), (@q, 'Give details and examples to explain the topic sentence', 1, 1), (@q, 'Tell jokes', 0, 2), (@q, 'Ask questions and stop writing', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What is the job of the concluding sentence?', 'The concluding sentence wraps up or repeats the main idea in a new way.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'To start a new story', 0, 0), (@q, 'To wrap up or repeat the main idea in a new way', 1, 1), (@q, 'To confuse the reader', 0, 2), (@q, 'To leave out important facts', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'Which one is a complete sentence?', '"The dog chased the ball." has a subject and a verb, so it is complete.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, '"Running in the park."', 0, 0), (@q, '"The dog chased the ball."', 1, 1), (@q, '"Because she was tired."', 0, 2), (@q, '"In the morning."', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What is a sentence fragment?', 'A fragment is an incomplete sentence missing a subject or a verb.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'A correct sentence', 0, 0), (@q, 'An incomplete sentence missing a subject or a verb', 1, 1), (@q, 'A very long paragraph', 0, 2), (@q, 'A chapter title', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What is a run-on sentence?', 'A run-on joins two complete sentences without proper punctuation.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'A sentence about running fast', 0, 0), (@q, 'Two complete sentences joined together without proper punctuation', 1, 1), (@q, 'A short question', 0, 2), (@q, 'A single word', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What punctuation mark goes at the end of a normal telling sentence?', 'A period (.) ends a normal telling (declarative) sentence.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'Comma (,)', 0, 0), (@q, 'Question mark (?)', 0, 1), (@q, 'Period (.)', 1, 2), (@q, 'Exclamation mark (!)', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'When do we use capital letters?', 'Capitals are used for names, places, and the start of sentences.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'For every single word', 0, 0), (@q, 'For names, places, and the start of sentences', 1, 1), (@q, 'Only for small words', 0, 2), (@q, 'Never', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'Which word shows addition (adding more info)?', '"Furthermore" (or "Also") adds more information.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'However', 0, 0), (@q, 'Furthermore (or Also)', 1, 1), (@q, 'Because', 0, 2), (@q, 'But', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'Which word shows contrast (opposite idea)?', '"However" shows contrast or an opposite idea.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'Moreover', 0, 0), (@q, 'However', 1, 1), (@q, 'First', 0, 2), (@q, 'And', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What is a descriptive adjective?', 'A descriptive adjective describes a noun, like "tall" or "blue".');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'A word that describes a noun (like "tall" or "blue")', 1, 0), (@q, 'A word that shows an action', 0, 1), (@q, 'A place name', 0, 2), (@q, 'A punctuation mark', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'Which word is a time transition?', '"Afterward" shows when something happens (time order).');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'Afterward', 1, 0), (@q, 'Therefore', 0, 1), (@q, 'Similarly', 0, 2), (@q, 'Because', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What is the subject in: "Cats sleep all day."?', 'The subject is who/what does the action: "Cats".');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'Sleep', 0, 0), (@q, 'All', 0, 1), (@q, 'Cats', 1, 2), (@q, 'Day', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What is a "peer review"?', 'Peer review means reading and giving helpful feedback on a classmate''s writing.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'Stealing someone''s paper', 0, 0), (@q, 'Reading and giving helpful feedback on a classmate''s writing', 1, 1), (@q, 'Reading a book alone', 0, 2), (@q, 'Taking a test', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'Why do we brainstorm before writing?', 'Brainstorming helps you think of ideas and plan what to write.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'To waste time', 0, 0), (@q, 'To think of ideas and plan what to write', 1, 1), (@q, 'To check spelling automatically', 0, 2), (@q, 'To finish the essay instantly', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What is the final step in writing?', 'Proofreading and fixing mistakes comes last.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'Brainstorming', 0, 0), (@q, 'Proofreading and fixing mistakes', 1, 1), (@q, 'Staring at paper', 0, 2), (@q, 'Throwing the paper away', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'Which pronoun replaces "Sarah"?', '"Sarah" is female singular, so the pronoun is "she".');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'He', 0, 0), (@q, 'She', 1, 1), (@q, 'It', 0, 2), (@q, 'They', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What does "unity" mean in a paragraph?', 'Unity means every sentence connects to the main idea.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'All sentences connect to the main idea', 1, 0), (@q, 'The paragraph is very long', 0, 1), (@q, 'There are no verbs', 0, 2), (@q, 'It is written in two languages', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What does "coherence" mean?', 'Coherence means sentences flow together smoothly and logically.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'Sentences flow together smoothly and logically', 1, 0), (@q, 'The text is hidden', 0, 1), (@q, 'Random sentences are put together', 0, 2), (@q, 'No one can read it', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'Which word connects two sentences using a comma (FANBOYS)?', 'FANBOYS are joining words; "But" connects with a comma.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'Because', 0, 0), (@q, 'But', 1, 1), (@q, 'Although', 0, 2), (@q, 'During', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What is the plural of "child"?', 'The plural of "child" is "children".');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'Childs', 0, 0), (@q, 'Children', 1, 1), (@q, 'Childies', 0, 2), (@q, 'Child', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'Which word shows cause and effect?', '"Therefore" shows cause and effect (result).');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'Therefore', 1, 0), (@q, 'Meanwhile', 0, 1), (@q, 'Yesterday', 0, 2), (@q, 'Suddenly', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'Which one is a concrete (physical) noun?', 'An apple is a physical thing you can touch.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'Happiness', 0, 0), (@q, 'Apple', 1, 1), (@q, 'Freedom', 0, 2), (@q, 'Idea', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'Where do you put the title of your paragraph?', 'The title goes centered at the top.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'At the very bottom', 0, 0), (@q, 'Centered at the top', 1, 1), (@q, 'On the back of the page', 0, 2), (@q, 'Inside a sentence', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What do you check when you "edit" a paper?', 'Editing checks grammar, spelling, and punctuation.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'Grammar, spelling, and punctuation', 1, 0), (@q, 'The color of the pen', 0, 1), (@q, 'How heavy the paper is', 0, 2), (@q, 'Nothing', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What do you focus on when you "revise" a paper?', 'Revising focuses on ideas, organization, and clarity.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'Spelling only', 0, 0), (@q, 'Ideas, organization, and making it clear', 1, 1), (@q, 'Page numbers', 0, 2), (@q, 'Font size', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What makes up a simple sentence?', 'One independent clause — one complete thought.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'One independent clause (one complete thought)', 1, 0), (@q, 'Three long stories', 0, 1), (@q, 'No verbs', 0, 2), (@q, 'Only nouns', 0, 3);
