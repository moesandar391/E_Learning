-- =====================================================================
-- Seed script: 20 extra sample questions for the Flyers quiz (quiz_id 5)
-- Result: question pool grows from 10 -> 30. Each student still gets a
-- random set of 10 (question_limit already set to 10, random_questions = 1).
-- =====================================================================

USE e_learning;

SET @quiz_id = 5;  -- Flyers Quiz

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What colour is the sky on a sunny day?', 'The sky looks blue during a sunny day.');
SET @q1 = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q1, 'Blue', 1, 0), (@q1, 'Red', 0, 1), (@q1, 'Green', 0, 2), (@q1, 'Black', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'I have two ____________ and two eyes.', 'We have two hands on our body.');
SET @q2 = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q2, 'hands', 1, 0), (@q2, 'heads', 0, 1), (@q2, 'noses', 0, 2), (@q2, 'backs', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'We use our ____________ to hear music.', 'Ears are the body part we hear with.');
SET @q3 = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q3, 'ears', 1, 0), (@q3, 'elbows', 0, 1), (@q3, 'knees', 0, 2), (@q3, 'shoulders', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'Which animal says "moo"?', 'A cow makes the sound "moo".');
SET @q4 = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q4, 'Cow', 1, 0), (@q4, 'Cat', 0, 1), (@q4, 'Dog', 0, 2), (@q4, 'Duck', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What do we call a baby dog?', 'A baby dog is called a puppy.');
SET @q5 = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q5, 'Puppy', 1, 0), (@q5, 'Kitten', 0, 1), (@q5, 'Chick', 0, 2), (@q5, 'Calf', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'The opposite of "hot" is ____________.', 'The opposite of hot is cold.');
SET @q6 = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q6, 'cold', 1, 0), (@q6, 'warm', 0, 1), (@q6, 'wet', 0, 2), (@q6, 'dry', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What fruit is yellow and curved?', 'A banana is yellow and curved.');
SET @q7 = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q7, 'Banana', 1, 0), (@q7, 'Apple', 0, 1), (@q7, 'Grapes', 0, 2), (@q7, 'Strawberry', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'She ____________ a book every night before bed.', 'The subject "She" takes "reads" in the present simple.');
SET @q8 = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q8, 'reads', 1, 0), (@q8, 'read', 0, 1), (@q8, 'reading', 0, 2), (@q8, 'is read', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'How many days are there in a week?', 'A week has seven days.');
SET @q9 = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q9, 'Seven', 1, 0), (@q9, 'Five', 0, 1), (@q9, 'Six', 0, 2), (@q9, 'Eight', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'Which of these is a colour?', 'Purple is a colour.');
SET @q10 = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q10, 'Purple', 1, 0), (@q10, 'Monday', 0, 1), (@q10, 'Summer', 0, 2), (@q10, 'School', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What do you wear on your feet?', 'Shoes are worn on your feet.');
SET @q11 = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q11, 'Shoes', 1, 0), (@q11, 'Hat', 0, 1), (@q11, 'Gloves', 0, 2), (@q11, 'Scarf', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'They ____________ to school by bus every day.', 'The subject "They" uses the base form "go".');
SET @q12 = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q12, 'go', 1, 0), (@q12, 'goes', 0, 1), (@q12, 'going', 0, 2), (@q12, 'gone', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'Which season comes after summer?', 'Autumn (fall) comes after summer.');
SET @q13 = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q13, 'Autumn', 1, 0), (@q13, 'Spring', 0, 1), (@q13, 'Winter', 0, 2), (@q13, 'Rainy', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'We sleep at ____________ at night.', 'We usually sleep at night.');
SET @q14 = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q14, 'night', 1, 0), (@q14, 'morning', 0, 1), (@q14, 'afternoon', 0, 2), (@q14, 'noon', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What is the plural of "child"?', 'The plural of child is children.');
SET @q15 = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q15, 'children', 1, 0), (@q15, 'childs', 0, 1), (@q15, 'childes', 0, 2), (@q15, 'childrens', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'A bird can ____________ in the sky.', 'Birds can fly in the sky.');
SET @q16 = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q16, 'fly', 1, 0), (@q16, 'swim', 0, 1), (@q16, 'walk', 0, 2), (@q16, 'run', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'Yesterday I ____________ my homework.', 'The past form of "do" is "did".');
SET @q17 = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q17, 'did', 1, 0), (@q17, 'do', 0, 1), (@q17, 'does', 0, 2), (@q17, 'doing', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'Which of these can you drink?', 'Milk is something you can drink.');
SET @q18 = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q18, 'Milk', 1, 0), (@q18, 'Bread', 0, 1), (@q18, 'Rice', 0, 2), (@q18, 'Egg', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'My birthday is ____________ May.', 'We use "in" before a month.');
SET @q19 = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q19, 'in', 1, 0), (@q19, 'on', 0, 1), (@q19, 'at', 0, 2), (@q19, 'by', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'Which word has the "sh" sound?', '"Ship" begins with the "sh" sound.');
SET @q20 = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q20, 'Ship', 1, 0), (@q20, 'Cat', 0, 1), (@q20, 'Dog', 0, 2), (@q20, 'Sun', 0, 3);
