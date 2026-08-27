-- =====================================================================
-- Seed script: Great Writing 1 Module Quiz (30 questions)
-- Run once in phpMyAdmin or MySQL CLI.
--   SET @module_id = 10;  (10 = Great Writing 1)
-- =====================================================================

SET @module_id = 10;  -- <-- Great Writing 1 module

INSERT INTO quizzes (module_id, quiz_title, passing_score, question_limit, time_limit, random_questions, random_answers)
VALUES (@module_id, 'Great Writing 1 Module Quiz', 70, 10, 0, 1, 1);

SET @quiz_id = LAST_INSERT_ID();

-- =====================================================================
-- Part 1: Paragraph Parts (Questions 1-10)
-- =====================================================================

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What is the main idea of a paragraph called?', 'The topic sentence expresses the main idea of a paragraph.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'Topic sentence', 1, 0), (@q, 'Shopping list', 0, 1), (@q, 'Joke', 0, 2), (@q, 'Book title', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'Where is the topic sentence usually found?', 'The topic sentence is usually found at the beginning of the paragraph.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'At the very end', 0, 0), (@q, 'At the beginning of the paragraph', 1, 1), (@q, 'In another book', 0, 2), (@q, 'On the back cover', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What do supporting sentences do?', 'Supporting sentences give details, facts, or examples about the topic.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'Change the subject', 0, 0), (@q, 'Give details, facts, or examples about the topic', 1, 1), (@q, 'Stop the story completely', 0, 2), (@q, 'Ask random questions', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What is the last sentence of a paragraph called?', 'The last sentence is called the concluding sentence.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'Concluding sentence', 1, 0), (@q, 'First sentence', 0, 1), (@q, 'Middle word', 0, 2), (@q, 'Title', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What does a concluding sentence do?', 'A concluding sentence wraps up the paragraph or restates the main idea.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'Starts a new topic', 0, 0), (@q, 'Wraps up the paragraph or restates the main idea', 1, 1), (@q, 'Leaves the reader confused', 0, 2), (@q, 'Erases the page', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What is a "title" of a paragraph?', 'A title is a short name placed at the top to tell what the text is about.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'A short name placed at the top to tell what the text is about', 1, 0), (@q, 'A long story', 0, 1), (@q, 'A punctuation mark', 0, 2), (@q, 'A verb', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What does "unity" mean in a paragraph?', 'Unity means all sentences are about the main topic.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'All sentences are about the main topic', 1, 0), (@q, 'The text is written in two languages', 0, 1), (@q, 'There are no verbs', 0, 2), (@q, 'The sentences are very messy', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What does "coherence" mean?', 'Coherence means sentences flow together smoothly and make sense.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'Sentences flow together smoothly and make sense', 1, 0), (@q, 'The ink is fading', 0, 1), (@q, 'The paragraph has no meaning', 0, 2), (@q, 'It is written backwards', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'Why do we brainstorm before writing?', 'We brainstorm to think of and collect ideas before writing.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'To waste time', 0, 0), (@q, 'To think of and collect ideas', 1, 1), (@q, 'To sleep', 0, 2), (@q, 'To close the notebook', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What is the step called when you check your paragraph for spelling and grammar?', 'This step is called proofreading or editing.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'Proofreading / Editing', 1, 0), (@q, 'Eating', 0, 1), (@q, 'Drawing', 0, 2), (@q, 'Running', 0, 3);

-- =====================================================================
-- Part 2: Sentences & Punctuation (Questions 11-20)
-- =====================================================================

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What is a complete sentence?', 'A complete sentence is a group of words with a subject and a verb that expresses a complete thought.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'A group of words with a subject and a verb that expresses a complete thought', 1, 0), (@q, 'Just one single letter', 0, 1), (@q, 'A random list of items', 0, 2), (@q, 'Only a punctuation mark', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What is a sentence fragment?', 'A sentence fragment is an incomplete sentence missing a subject or a verb.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'A perfect story', 0, 0), (@q, 'An incomplete sentence missing a subject or a verb', 1, 1), (@q, 'A long paragraph', 0, 2), (@q, 'A capital letter', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What punctuation mark must be at the end of a normal statement?', 'A period (.) must be at the end of a normal statement.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'Question mark (?)', 0, 0), (@q, 'Period (.)', 1, 1), (@q, 'Comma (,)', 0, 2), (@q, 'Exclamation mark (!)', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'When do we use capital letters?', 'We use capital letters for names of people, places, and the start of sentences.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'For names of people, places, and the start of sentences', 1, 0), (@q, 'For every single word', 0, 1), (@q, 'Only at the bottom of the page', 0, 2), (@q, 'Never', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What is a run-on sentence?', 'A run-on sentence is two sentences joined together incorrectly without proper punctuation or connectors.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'Two sentences joined together incorrectly without proper punctuation or connectors', 1, 0), (@q, 'A sentence about running fast', 0, 1), (@q, 'A very short sentence', 0, 2), (@q, 'A word search puzzle', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'Which word is a time transition showing the first step?', '"First" or "First of all" shows the first step.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'First / First of all', 1, 0), (@q, 'However', 0, 1), (@q, 'Because', 0, 2), (@q, 'But', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'Which word shows an opposite idea (contrast)?', '"However" or "On the other hand" shows contrast.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'However / On the other hand', 1, 0), (@q, 'And', 0, 1), (@q, 'Next', 0, 2), (@q, 'Also', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'Which word shows a result?', '"Therefore" or "As a result" shows a result.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'Therefore / As a result', 1, 0), (@q, 'Yesterday', 0, 1), (@q, 'Under', 0, 2), (@q, 'Cat', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What is a peer review?', 'Peer review is reading and helping a classmate check their writing.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'Reading and helping a classmate check their writing', 1, 0), (@q, 'Fighting with a friend', 0, 1), (@q, 'Taking a school bus', 0, 2), (@q, 'Buying a pen', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What should you focus on when you "revise" a paragraph?', 'When revising, focus on ideas, organization, and making sentences clearer.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'Ideas, organization, and making sentences clearer', 1, 0), (@q, 'Changing the paper color', 0, 1), (@q, 'Writing smaller', 0, 2), (@q, 'Erasing the title only', 0, 3);

-- =====================================================================
-- Part 3: Words & Types (Questions 21-30)
-- =====================================================================

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What is a noun?', 'A noun is a word that names a person, place, thing, or idea.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'A word that names a person, place, thing, or idea', 1, 0), (@q, 'A fast runner', 0, 1), (@q, 'A punctuation mark', 0, 2), (@q, 'A number only', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'Which word is a noun?', '"School" is a noun because it names a place.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'Run', 0, 0), (@q, 'School', 1, 1), (@q, 'Quickly', 0, 2), (@q, 'Happy', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What is a verb?', 'A verb is an action word or state of being.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'An action word or state of being', 1, 0), (@q, 'A color name', 0, 1), (@q, 'A shape', 0, 2), (@q, 'A punctuation mark', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'Which word is a verb?', '"Jump" is a verb because it shows an action.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'Jump', 1, 0), (@q, 'Apple', 0, 1), (@q, 'Desk', 0, 2), (@q, 'Tall', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What is an adjective?', 'An adjective is a word that describes a noun (e.g., big, blue, smart).');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'A word that describes a noun (e.g., big, blue, smart)', 1, 0), (@q, 'A word that shows time', 0, 1), (@q, 'A person''s name', 0, 2), (@q, 'A number', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'Which word is an adjective?', '"Beautiful" is an adjective because it describes a noun.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'Beautiful', 1, 0), (@q, 'Swim', 0, 1), (@q, 'Slowly', 0, 2), (@q, 'Box', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What is a pronoun?', 'A pronoun is a word that replaces a noun (e.g., he, she, it, they).');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'A word that replaces a noun (e.g., he, she, it, they)', 1, 0), (@q, 'A long story', 0, 1), (@q, 'A math formula', 0, 2), (@q, 'A type of paper', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'Which pronoun replaces the name "Sarah"?', '"She" is the pronoun that replaces the female name "Sarah".');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'He', 0, 0), (@q, 'She', 1, 1), (@q, 'It', 0, 2), (@q, 'They', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What is a descriptive paragraph?', 'A descriptive paragraph paints a picture with words about how something looks, feels, or sounds.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'A paragraph that paints a picture with words about how something looks, feels, or sounds', 1, 0), (@q, 'A math formula', 0, 1), (@q, 'A recipe for cooking rice', 0, 2), (@q, 'A computer code', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What is a narrative paragraph?', 'A narrative paragraph tells a story about an event that happened.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'A paragraph that tells a story about an event that happened', 1, 0), (@q, 'A list of dictionary definitions', 0, 1), (@q, 'A weather report', 0, 2), (@q, 'A drawing instructions manual', 0, 3);
