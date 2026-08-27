-- =====================================================================
-- Seed script: Great Writing 2 Module Quiz (30 questions)
-- Run once in phpMyAdmin or MySQL CLI.
--   SET @module_id = 11;  (11 = Great Writing 2)
-- =====================================================================

SET @module_id = 11;  -- <-- Great Writing 2 module

INSERT INTO quizzes (module_id, quiz_title, passing_score, question_limit, time_limit, random_questions, random_answers)
VALUES (@module_id, 'Great Writing 2 Module Quiz', 70, 10, 0, 1, 1);

SET @quiz_id = LAST_INSERT_ID();

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What is the main purpose of a thesis statement?', 'The thesis states the main argument or focus of the essay.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'To tell a joke', 0, 0), (@q, 'To state the main argument or focus of the essay', 1, 1), (@q, 'To list sources', 0, 2), (@q, 'To write the title', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'Where do you usually find the thesis statement?', 'At the end of the introduction paragraph.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'At the end of the introduction paragraph', 1, 0), (@q, 'On the back cover', 0, 1), (@q, 'In the conclusion', 0, 2), (@q, 'In the middle of nowhere', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What are the three main parts of an essay?', 'Introduction, Body, and Conclusion.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'Beginning, middle, end', 0, 0), (@q, 'Introduction, Body, Conclusion', 1, 1), (@q, 'Thesis, Hook, Summary', 0, 2), (@q, 'Title, Picture, Index', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What is a topic sentence in a body paragraph?', 'It gives the main idea of that specific paragraph.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'The main idea of that specific paragraph', 1, 0), (@q, 'The final sentence of the whole paper', 0, 1), (@q, 'A book quote', 0, 2), (@q, 'A transition word', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What does a supporting detail do?', 'It proves or explains the topic sentence with examples.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'Proves or explains the paragraph''s topic sentence with examples', 1, 0), (@q, 'Changes the subject', 0, 1), (@q, 'Shortens the essay', 0, 2), (@q, 'Confuses the reader', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What is a counterargument?', 'An opposing view or objection against your idea.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'An opposing view or objection against your idea', 1, 0), (@q, 'A supporting fact', 0, 1), (@q, 'The final conclusion', 0, 2), (@q, 'A spelling check', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What is a rebuttal?', 'A response that answers and disproves the counterargument.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'Agreeing with the enemy', 0, 0), (@q, 'A response that answers and disproves the counterargument', 1, 1), (@q, 'A book title', 0, 2), (@q, 'A bibliography', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'Which word shows a result?', '"Consequently" (or "As a result") shows a result.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'However', 0, 0), (@q, 'Consequently (or As a result)', 1, 1), (@q, 'Furthermore', 0, 2), (@q, 'Meanwhile', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'Which word introduces an example?', '"For instance" introduces an example.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'For instance', 1, 0), (@q, 'In contrast', 0, 1), (@q, 'On the other hand', 0, 2), (@q, 'Therefore', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What is plagiarism?', 'Copying someone''s words or ideas without giving them credit.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'Writing your own poem', 0, 0), (@q, 'Copying someone else''s words or ideas without giving them credit', 1, 1), (@q, 'Fixing grammar', 0, 2), (@q, 'Translating text', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What is a paraphrase?', 'Rewriting someone else''s idea in your own words.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'Copying word-for-word', 0, 0), (@q, 'Rewriting someone else''s idea in your own words', 1, 1), (@q, 'Deleting sentences', 0, 2), (@q, 'Shortening a word', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What is a direct quotation?', 'Using exact words from a source inside quotation marks (" ").');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'Summarizing a chapter', 0, 0), (@q, 'Using exact words from a source inside quotation marks (" ")', 1, 1), (@q, 'Guessing what an author meant', 0, 2), (@q, 'Changing all the verbs', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What is unity in an essay?', 'All parts connect back to the central thesis.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'All parts connect back to the central thesis', 1, 0), (@q, 'All words have the same length', 0, 1), (@q, 'Different people write each page', 0, 2), (@q, 'No punctuation is used', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What is coherence?', 'Sentences and paragraphs flow smoothly together.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'Sentences and paragraphs flow smoothly together', 1, 0), (@q, 'Random arrangement of ideas', 0, 1), (@q, 'Using only tiny words', 0, 2), (@q, 'Writing without a plan', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What is the job of the conclusion?', 'Restate the thesis, summarize points, and leave a final thought.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'To start brand new arguments', 0, 0), (@q, 'To restate the thesis, summarize points, and leave a final thought', 1, 1), (@q, 'To list random definitions', 0, 2), (@q, 'To repeat the intro word-for-word', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What does a descriptive essay do?', 'Paints a picture using senses (sight, sound, smell, feel).');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'Solves math equations', 0, 0), (@q, 'Paints a picture using senses (sight, sound, smell, feel)', 1, 1), (@q, 'Proves a law', 0, 2), (@q, 'Gives cooking instructions', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What is a process analysis essay?', 'Explains how to do something step-by-step.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'Explains how to do something step-by-step', 1, 0), (@q, 'Compares history', 0, 1), (@q, 'Tells a monster story', 0, 2), (@q, 'Argues about taxes', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What is a cause-and-effect essay?', 'Explains why something happens and what happens because of it.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'Explains why something happens and what happens because of it', 1, 0), (@q, 'Describes a sunset', 0, 1), (@q, 'Lists vocabulary', 0, 2), (@q, 'Keeps a diary', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What is a compare-and-contrast essay?', 'Discusses similarities and differences between subjects.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'Discusses similarities and differences between subjects', 1, 0), (@q, 'Tells a joke', 0, 1), (@q, 'Gives a recipe', 0, 2), (@q, 'Writes a biography', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What is an argumentative essay?', 'Takes a stand on an issue and tries to persuade the reader with logic.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'Takes a stand on an issue and tries to persuade the reader with logic', 1, 0), (@q, 'Tells a dream', 0, 1), (@q, 'Gives computer instructions', 0, 2), (@q, 'Contains only poetry', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What is a "hook" in an introduction?', 'An interesting opening sentence to grab the reader''s attention.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'A tool to catch fish', 0, 0), (@q, 'An interesting opening sentence to grab the reader''s attention', 1, 1), (@q, 'The final sentence of the paper', 0, 2), (@q, 'A formatting mistake', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What is peer editing?', 'Helping a classmate check and improve their draft.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'Stealing someone''s work', 0, 0), (@q, 'Helping a classmate check and improve their draft', 1, 1), (@q, 'Reading alone in the library', 0, 2), (@q, 'Printing copies', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What is a run-on sentence?', 'Joining two complete sentences without proper punctuation or connectors.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'Running while writing', 0, 0), (@q, 'Joining two complete sentences without proper punctuation or connectors', 1, 1), (@q, 'Using a comma correctly', 0, 2), (@q, 'Starting with a capital letter', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What is a comma splice?', 'Joining two complete sentences with only a comma instead of a conjunction.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'Joining two complete sentences with only a comma instead of a conjunction', 1, 0), (@q, 'Cutting a comma in half', 0, 1), (@q, 'Missing a period', 0, 2), (@q, 'Putting a period instead of a comma', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What is passive voice?', 'When the object receives the action, e.g., "The book was written by Mark".');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'When the subject does the action', 0, 0), (@q, 'When the object receives the action (e.g., "The book was written by Mark")', 1, 1), (@q, 'Whispering', 0, 2), (@q, 'Writing without verbs', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What is active voice?', 'When the subject does the action directly, e.g., "Mark wrote the book".');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'When the subject does the action directly (e.g., "Mark wrote the book")', 1, 0), (@q, 'When the object is the main focus', 0, 1), (@q, 'Shouting out loud', 0, 2), (@q, 'Using passive verbs only', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'Why is academic tone important?', 'It keeps writing objective, formal, and professional without slang.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'It makes the essay look longer', 0, 0), (@q, 'It keeps writing objective, formal, and professional without slang', 1, 1), (@q, 'It allows internet slang', 0, 2), (@q, 'It confuses the teacher', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What is brainstorming?', 'Writing down ideas quickly before outlining.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'Having a headache', 0, 0), (@q, 'Writing down ideas quickly before outlining', 1, 1), (@q, 'Checking final spelling', 0, 2), (@q, 'Memorizing a textbook', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What is an outline?', 'A structured plan showing paragraph order and key points.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'A drawing of an animal', 0, 0), (@q, 'A structured plan showing paragraph order and key points', 1, 1), (@q, 'The border of a page', 0, 2), (@q, 'A list of sources', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What is proofreading?', 'Checking for final spelling, punctuation, and typo errors.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'Writing the first draft', 0, 0), (@q, 'Checking for final spelling, punctuation, and typo errors', 1, 1), (@q, 'Interviewing an author', 0, 2), (@q, 'Designing a cover', 0, 3);
