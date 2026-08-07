-- =====================================================================
-- Seed script: Elementary Module Quiz (30 questions)
-- Run once in phpMyAdmin or MySQL CLI.
--   SET @module_id = 17;  (17 = Elemetary) -- edit if needed.
-- =====================================================================

SET @module_id = 17;  -- <-- Elementary module

-- 1. Create the quiz
INSERT INTO quizzes (module_id, quiz_title, passing_score, question_limit, time_limit, random_questions, random_answers)
VALUES (@module_id, 'Elementary Module Quiz', 70, 100, 0, 1, 1);

SET @quiz_id = LAST_INSERT_ID();

-- =====================================================================
-- Lesson 1: Be Verbs (Am, Is, Are)
-- =====================================================================
INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'Choose the correct form of the verb "to be": "She ______ a teacher at the local school."', '"She" is third-person singular, so we use "is".');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'am', 0, 0), (@q, 'is', 1, 1), (@q, 'are', 0, 2), (@q, 'be', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'Complete the sentence: "They ______ very happy with their new apartment."', '"They" is plural, so we use "are".');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'am', 0, 0), (@q, 'is', 0, 1), (@q, 'are', 1, 2), (@q, 'be', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What is the correct form for the first-person singular pronoun? "I ______ a student."', 'The first-person singular pronoun "I" takes "am".');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'am', 1, 0), (@q, 'is', 0, 1), (@q, 'are', 0, 2), (@q, 'be', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'Choose the correct option to fill in the blank: "______ you ready for the presentation?"', '"You" takes the plural form "are" in questions: "Are you ready?"');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'Am', 0, 0), (@q, 'Is', 0, 1), (@q, 'Are', 1, 2), (@q, 'Be', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'Fill in the blank: "My brother and I ______ students at the university."', '"My brother and I" means "we", so the verb is "are".');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'am', 0, 0), (@q, 'is', 0, 1), (@q, 'are', 1, 2), (@q, 'be', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'Choose the correct option: "______ the weather nice today?"', '"Weather" is singular and third person, so we ask "Is the weather nice today?"');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'Am', 0, 0), (@q, 'Is', 1, 1), (@q, 'Are', 0, 2), (@q, 'Be', 0, 3);

-- =====================================================================
-- Lesson 2: Be Verbs - Was and Were (Past Tense)
-- =====================================================================
INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'Complete the past tense sentence: "He ______ at the library yesterday afternoon."', '"He" (singular) in the past takes "was".');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'was', 1, 0), (@q, 'were', 0, 1), (@q, 'is', 0, 2), (@q, 'are', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'Choose the correct verb form: "We ______ late for the meeting last Monday."', '"We" (plural) in the past takes "were".');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'was', 0, 0), (@q, 'were', 1, 1), (@q, 'are', 0, 2), (@q, 'is', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'Fill in the blank: "I ______ tired after running the marathon."', '"I" in the past tense takes "was".');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'was', 1, 0), (@q, 'were', 0, 1), (@q, 'am', 0, 2), (@q, 'been', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'Select the correct word: "Where ______ you last night at 8 o''clock?"', '"You" in the past takes "were": "Where were you last night?"');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'was', 0, 0), (@q, 'were', 1, 1), (@q, 'are', 0, 2), (@q, 'did', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'Complete the sentence: "The children ______ very quiet during the movie."', '"Children" is plural, so the past form is "were".');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'was', 0, 0), (@q, 'were', 1, 1), (@q, 'are', 0, 2), (@q, 'is', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'Choose the correct negative form: "I ______ happy with my test results yesterday."', 'The past negative of "I was" is "I wasn''t".');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'wasn''t', 1, 0), (@q, 'weren''t', 0, 1), (@q, 'amn''t', 0, 2), (@q, 'not was', 0, 3);

-- =====================================================================
-- Lesson 3: Modal Verbs - Can
-- =====================================================================
INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'Which sentence correctly uses the modal verb "can" to express ability?', 'After "can" we use the base form of the verb: "She can play the piano."');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'She can plays the piano very well.', 0, 0), (@q, 'She can play the piano very well.', 1, 1), (@q, 'She can playing the piano very well.', 0, 2), (@q, 'She can to play the piano very well.', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'How do you form a negative sentence using "can"?', 'The negative of "can" is "cannot" (can''t), so "He cannot swim" is correct.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'He cannot swim across the deep pool.', 1, 0), (@q, 'He cans not swim across the deep pool.', 0, 1), (@q, 'He not can swim across the deep pool.', 0, 2), (@q, 'He doesn''t can swim across the deep pool.', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'Choose the correct interrogative (question) form:', 'To ask a question, invert: "Can you speak Spanish?"');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'Can you to speak Spanish?', 0, 0), (@q, 'Do you can speak Spanish?', 0, 1), (@q, 'Can you speak Spanish?', 1, 2), (@q, 'Are you can speak Spanish?', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'Complete the sentence: "Birds ______ fly high in the sky, but humans cannot without aircraft."', '"Can" expresses ability and stays the same for all subjects.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'can', 1, 0), (@q, 'cans', 0, 1), (@q, 'could', 0, 2), (@q, 'is able', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'Select the correct sentence:', 'After "can" we use the base verb: "They can run very fast."');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'They can runs very fast.', 0, 0), (@q, 'They can running very fast.', 0, 1), (@q, 'They can run very fast.', 1, 2), (@q, 'They can to run very fast.', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'Complete the question: "______ he fix your broken computer?"', 'Questions with "can" start with "Can": "Can he fix your computer?"');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'Can', 1, 0), (@q, 'Cans', 0, 1), (@q, 'Does can', 0, 2), (@q, 'Is can', 0, 3);

-- =====================================================================
-- Lesson 4: Singular and Plurals
-- =====================================================================
INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What is the correct plural form of the noun "child"?', 'The plural of "child" is "children" (irregular noun).');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'Childs', 0, 0), (@q, 'Childes', 0, 1), (@q, 'Children', 1, 2), (@q, 'Childrens', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'Which of the following nouns changes its spelling completely in the plural form?', '"Mouse" becomes "mice", a completely different spelling.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'Book -> Books', 0, 0), (@q, 'Mouse -> Mice', 1, 1), (@q, 'Cat -> Cats', 0, 2), (@q, 'Apple -> Apples', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What is the correct plural form for the noun "party"?', 'Nouns ending in consonant + y change to "ies": party -> parties.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'Partys', 0, 0), (@q, 'Partyes', 0, 1), (@q, 'Parties', 1, 2), (@q, 'Partis', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'Which noun remains exactly the same in both its singular and plural forms?', '"Fish" has the same form in singular and plural.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'Box', 0, 0), (@q, 'Fish', 1, 1), (@q, 'City', 0, 2), (@q, 'Bus', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What is the correct plural form of the noun "box"?', 'Nouns ending in -x add "es": box -> boxes.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'Boxs', 0, 0), (@q, 'Boxes', 1, 1), (@q, 'Boxies', 0, 2), (@q, 'Boxen', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'Which of the following words is always plural and takes a plural verb?', '"Scissors" is a plural-only noun and takes a plural verb.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'Scissors', 1, 0), (@q, 'Water', 0, 1), (@q, 'Sugar', 0, 2), (@q, 'Information', 0, 3);

-- =====================================================================
-- Lesson 5: Tag Questions
-- =====================================================================
INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'Complete the tag question: "You like coffee, ______?"', 'Positive statement with "like" takes a negative tag with the helper "do": "don''t you?".');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'don''t you', 1, 0), (@q, 'do you', 0, 1), (@q, 'aren''t you', 0, 2), (@q, 'isn''t it', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'Choose the correct question tag: "She is a doctor, ______?"', 'Positive "is" takes the negative tag "isn''t she?".');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'isn''t she', 1, 0), (@q, 'is she', 0, 1), (@q, 'doesn''t she', 0, 2), (@q, 'does she', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'Fill in the blank with the correct tag: "They can swim well, ______?"', 'Positive modal "can" takes the negative tag "can''t they?".');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'can they', 0, 0), (@q, 'can''t they', 1, 1), (@q, 'do they', 0, 2), (@q, 'don''t they', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'Complete the sentence: "It was a wonderful movie, ______?"', 'Positive past "was" takes the negative tag "wasn''t it?".');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'wasn''t it', 1, 0), (@q, 'was it', 0, 1), (@q, 'didn''t it', 0, 2), (@q, 'weren''t it', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'Complete the tag question: "He doesn''t eat meat, ______?"', 'Negative statement with "doesn''t" takes a positive tag: "does he?".');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'is he', 0, 0), (@q, 'isn''t he', 0, 1), (@q, 'does he', 1, 2), (@q, 'doesn''t he', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'Fill in the blank: "You will come to the party, ______?"', 'Positive "will" takes the negative tag "won''t you?".');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'will you', 0, 0), (@q, 'won''t you', 1, 1), (@q, 'do you', 0, 2), (@q, 'don''t you', 0, 3);