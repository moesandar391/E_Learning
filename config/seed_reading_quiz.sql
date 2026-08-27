-- =====================================================================
-- Seed script: Reading Module Quiz (30 questions)
-- Run once in phpMyAdmin or MySQL CLI.
--   SET @module_id = 15;  (15 = Reading)
-- =====================================================================

SET @module_id = 15;  -- <-- Reading module

INSERT INTO quizzes (module_id, quiz_title, passing_score, question_limit, time_limit, random_questions, random_answers)
VALUES (@module_id, 'Reading Module Quiz', 70, 10, 0, 1, 1);

SET @quiz_id = LAST_INSERT_ID();

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What is "skimming"?', 'Quickly looking over a text to get the general idea.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'Reading every single word very slowly', 0, 0), (@q, 'Quickly looking over a text to get the general idea', 1, 1), (@q, 'Looking for a phone number', 0, 2), (@q, 'Skipping the book', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What is "scanning"?', 'Rapidly searching a text to find a specific fact, name, or number.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'Reading a novel overnight', 0, 0), (@q, 'Rapidly searching a text to find a specific fact, name, or number', 1, 1), (@q, 'Fixing spelling', 0, 2), (@q, 'Drawing pictures', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What is the main idea of a reading passage?', 'The central message the author wants to share.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'The font size', 0, 0), (@q, 'The central message the author wants to share', 1, 1), (@q, 'The publisher''s name', 0, 2), (@q, 'A small detail', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'Where is a paragraph''s main idea usually found?', 'In the topic sentence.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'In the topic sentence', 1, 0), (@q, 'On the back cover', 0, 1), (@q, 'In page numbers', 0, 2), (@q, 'In the index', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What does "reading between the lines" mean?', 'Figuring out hidden meanings not directly written in the text.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'Reading blank side margins', 0, 0), (@q, 'Figuring out hidden meanings not directly written in the text', 1, 1), (@q, 'Skipping lines', 0, 2), (@q, 'Reading upside down', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What is a context clue?', 'Hints around a difficult word that help you guess its meaning.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'A treasure map', 0, 0), (@q, 'Hints around a difficult word that help you guess its meaning', 1, 1), (@q, 'A dictionary at the back', 0, 2), (@q, 'A grammar rule', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What is a synonym?', 'A word with the same or similar meaning.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'A word with the opposite meaning', 0, 0), (@q, 'A word with the same or similar meaning', 1, 1), (@q, 'A spelling error', 0, 2), (@q, 'A person''s name', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What is an antonym?', 'A word with the opposite meaning.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'A word with the same meaning', 0, 0), (@q, 'A word with the opposite meaning', 1, 1), (@q, 'A punctuation mark', 0, 2), (@q, 'A prefix', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What is a fact?', 'A statement that can be proven true with evidence.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'A personal feeling', 0, 0), (@q, 'A statement that can be proven true with evidence', 1, 1), (@q, 'A made-up story', 0, 2), (@q, 'A guess', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What is an opinion?', 'A personal belief or view that cannot be proven true for everyone.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'A scientific law', 0, 0), (@q, 'A personal belief or view that cannot be proven true for everyone', 1, 1), (@q, 'A historical date', 0, 2), (@q, 'A math answer', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What is the author''s purpose?', 'Why the author wrote the text (to inform, persuade, or entertain).');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'The weight of the book', 0, 0), (@q, 'Why the author wrote the text (to inform, persuade, or entertain)', 1, 1), (@q, 'The number of chapters', 0, 2), (@q, 'The cover color', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What is the author''s tone?', 'The author''s attitude toward the subject (e.g., serious, funny, angry).');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'How loud they speak', 0, 0), (@q, 'The author''s attitude toward the subject (e.g., serious, funny, angry)', 1, 1), (@q, 'The font style', 0, 2), (@q, 'The publication year', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What is a summary?', 'A short statement of the main points in your own words.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'Copying an entire book', 0, 0), (@q, 'A short statement of the main points in your own words', 1, 1), (@q, 'A list of definitions', 0, 2), (@q, 'The introduction chapter', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What is a prefix?', 'Letters added to the beginning of a word to change its meaning (e.g., "un-").');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'Letters added to the end of a word', 0, 0), (@q, 'Letters added to the beginning of a word to change its meaning (e.g., "un-")', 1, 1), (@q, 'A punctuation mark', 0, 2), (@q, 'A separate sentence', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What is a suffix?', 'Letters added to the end of a word (e.g., "-ly").');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'Letters added to the end of a word (e.g., "-ly")', 1, 0), (@q, 'A word added before a noun', 0, 1), (@q, 'A chapter title', 0, 2), (@q, 'An index entry', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What is a root word?', 'The base word before adding prefixes or suffixes.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'Plant roots', 0, 0), (@q, 'The base word before adding prefixes or suffixes', 1, 1), (@q, 'The last word of a paragraph', 0, 2), (@q, 'A misspelled word', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What does chronological order mean?', 'Arranging events in the order they happened in time.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'Alphabetical order', 0, 0), (@q, 'Arranging events in the order they happened in time', 1, 1), (@q, 'Order of importance', 0, 2), (@q, 'Random sorting', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What is a cause-and-effect structure?', 'Explaining why something happened and what resulted from it.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'Explaining why something happened and what resulted from it', 1, 0), (@q, 'Comparing unrelated objects', 0, 1), (@q, 'Describing looks', 0, 2), (@q, 'Listing items alphabetically', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What is a compare-and-contrast structure?', 'Examining similarities and differences between subjects.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'Explaining history', 0, 0), (@q, 'Examining similarities and differences between subjects', 1, 1), (@q, 'Giving step instructions', 0, 2), (@q, 'Telling a fable', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What are charts and graphs used for?', 'Visually organizing and clarifying data or information.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'Taking up blank space', 0, 0), (@q, 'Visually organizing and clarifying data or information', 1, 1), (@q, 'Confusing the reader', 0, 2), (@q, 'Replacing words with no meaning', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What is a pronoun reference?', 'Finding out what noun a pronoun stands for (e.g., "John lost his keys").');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'Finding out what noun a pronoun stands for (e.g., "John lost his keys")', 1, 0), (@q, 'Changing a noun to a verb', 0, 1), (@q, 'Deleting pronouns', 0, 2), (@q, 'Spelling backward', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What does "infer" mean?', 'To figure something out using clues and reasoning.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'To state loudly', 0, 0), (@q, 'To figure something out using clues and reasoning', 1, 1), (@q, 'To copy text', 0, 2), (@q, 'To ignore messages', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What is a heading used for?', 'To divide a text into smaller sections and show what each section is about.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'To hide secret codes', 0, 0), (@q, 'To divide a text into smaller sections and show what each section is about', 1, 1), (@q, 'To replace page numbers', 0, 2), (@q, 'To confuse readers', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What is intensive reading?', 'Slow, detailed reading of a short text to catch every single detail and grammar rule.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'Slow, detailed reading of a short text to catch every single detail and grammar rule', 1, 0), (@q, 'Reading a 500-page book in 10 minutes', 0, 1), (@q, 'Skimming headlines', 0, 2), (@q, 'Looking at pictures only', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What is extensive reading?', 'Reading long books or stories fluently for pleasure and general understanding.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'Reading long books or stories fluently for pleasure and general understanding', 1, 0), (@q, 'Analyzing every single syllable', 0, 1), (@q, 'Memorizing grammar tables', 0, 2), (@q, 'Translating dictionaries page-by-page', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What is a glossary?', 'An alphabetical list of definitions at the back of a book.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'An alphabetical list of definitions at the back of a book', 1, 0), (@q, 'A list of chapters at the front', 0, 1), (@q, 'Page numbers index', 0, 2), (@q, 'A picture gallery', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What is an index?', 'An alphabetical list of topics and page numbers at the back of a book.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'An alphabetical list of topics and page numbers at the back of a book', 1, 0), (@q, 'The front cover', 0, 1), (@q, 'Author bio summary', 0, 2), (@q, 'Character list', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What is a table of contents?', 'A list of chapters and page numbers at the front of a book.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'A list of chapters and page numbers at the front of a book', 1, 0), (@q, 'A table for eating meals', 0, 1), (@q, 'A list of glossary words', 0, 2), (@q, 'A price list', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What does "objective" mean in a text?', 'Based on pure facts without personal feelings or bias.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'Full of personal emotions and bias', 0, 0), (@q, 'Based on pure facts without personal feelings or bias', 1, 1), (@q, 'Fictional story style', 0, 2), (@q, 'Very hard to read', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What is a narrative text?', 'A story or account of events (real or fictional).');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'A science report', 0, 0), (@q, 'A story or account of events (real or fictional)', 1, 1), (@q, 'A financial sheet', 0, 2), (@q, 'Rule book', 0, 3);
