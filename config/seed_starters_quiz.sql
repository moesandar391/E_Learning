-- =====================================================================
-- Seed script: Foundation for Starters Module Quiz (20 questions)
-- Run once in phpMyAdmin or MySQL CLI.
--   SET @module_id = 5;  (5 = Foundation for Starters)
-- =====================================================================

SET @module_id = 5;  -- <-- Foundation for Starters module

-- 1. Create the quiz
INSERT INTO quizzes (module_id, quiz_title, passing_score, question_limit, time_limit, random_questions, random_answers)
VALUES (@module_id, 'Foundation for Starters Module Quiz', 70, 100, 0, 1, 1);

SET @quiz_id = LAST_INSERT_ID();

-- =====================================================================
-- Lesson 1: Toys and Games
-- =====================================================================
INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'Which toy is traditionally made of fabric, stuffed with soft material, and shaped like a bear or other animal?', 'A teddy bear is a soft, stuffed toy in the shape of a bear.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'Doll', 0, 0), (@q, 'Teddy bear', 1, 1), (@q, 'Action figure', 0, 2), (@q, 'Robot', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What classic board game involves moving numbered pieces across a grid to climb ladders and avoid sliding down snakes?', 'Snakes and Ladders uses ladders to climb and snakes to slide down.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'Chess', 0, 0), (@q, 'Snakes and Ladders', 1, 1), (@q, 'Checkers', 0, 2), (@q, 'Monopoly', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'Which toy features a spool wound with string that spins up and down on a vertical line?', 'A yo-yo consists of a spool that spins up and down on a string.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'Frisbee', 0, 0), (@q, 'Yo-yo', 1, 1), (@q, 'Kite', 0, 2), (@q, 'Hula hoop', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What game involves building structures, vehicles, or animals using colorful plastic interlocking bricks?', 'Building blocks / Lego are colourful interlocking plastic bricks used to build things.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'Dominoes', 0, 0), (@q, 'Building blocks / Lego', 1, 1), (@q, 'Marbles', 0, 2), (@q, 'Puzzles', 0, 3);

-- =====================================================================
-- Lesson 2: Food and Drink
-- =====================================================================
INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'Which yellow, curved fruit grows in clusters and is a popular snack for both humans and monkeys?', 'A banana is a yellow, curved fruit that grows in clusters.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'Apple', 0, 0), (@q, 'Banana', 1, 1), (@q, 'Orange', 0, 2), (@q, 'Grape', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What common white liquid is produced by mammals (like cows and goats) and is rich in calcium?', 'Milk is the white liquid produced by mammals and is rich in calcium.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'Juice', 0, 0), (@q, 'Milk', 1, 1), (@q, 'Tea', 0, 2), (@q, 'Water', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'Which baked food item is made primarily from flour, water, and yeast, and is often used to make sandwiches?', 'Bread is baked from flour, water, and yeast, and is used for sandwiches.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'Bread', 1, 0), (@q, 'Rice', 0, 1), (@q, 'Pasta', 0, 2), (@q, 'Cereal', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What cold, sweet frozen dessert is typically served in a cone or a bowl?', 'Ice cream is a cold, sweet frozen dessert served in a cone or bowl.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'Soup', 0, 0), (@q, 'Ice cream', 1, 1), (@q, 'Yogurt', 0, 2), (@q, 'Butter', 0, 3);

-- =====================================================================
-- Lesson 3: Animal Vocabulary
-- =====================================================================
INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'Which large farm animal is known for producing wool and making a "baa" sound?', 'Sheep produce wool and make a "baa" sound.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'Cow', 0, 0), (@q, 'Sheep', 1, 1), (@q, 'Horse', 0, 2), (@q, 'Pig', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What large, wild African animal has a very long neck and eats leaves from tall trees?', 'A giraffe has a long neck and eats leaves from tall trees.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'Zebra', 0, 0), (@q, 'Elephant', 0, 1), (@q, 'Giraffe', 1, 2), (@q, 'Lion', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'Which small domestic animal is a popular house pet, known for purring and chasing mice?', 'A cat is a small domestic pet that purrs and chases mice.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'Cat', 1, 0), (@q, 'Dog', 0, 1), (@q, 'Rabbit', 0, 2), (@q, 'Hamster', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What farm animal is famous for living in muddy areas and making an "oink" sound?', 'A pig lives in muddy areas and makes an "oink" sound.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'Goat', 0, 0), (@q, 'Duck', 0, 1), (@q, 'Pig', 1, 2), (@q, 'Chicken', 0, 3);

-- =====================================================================
-- Lesson 4: Clothes Vocabulary
-- =====================================================================
INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'Which clothing item is worn on the feet inside shoes, often made of cotton or wool?', 'Socks are worn on the feet inside shoes and are often made of cotton or wool.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'Trousers', 0, 0), (@q, 'Socks', 1, 1), (@q, 'Hat', 0, 2), (@q, 'Gloves', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What piece of clothing covers the upper body, typically has short or long sleeves, and buttons up the front?', 'A shirt covers the upper body, has sleeves, and buttons up the front.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'Skirt', 0, 0), (@q, 'Shirt', 1, 1), (@q, 'Jacket', 0, 2), (@q, 'Scarf', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'Which garment is worn around the waist and legs, covering the lower body separately for each leg?', 'Trousers / pants cover the lower body with a separate section for each leg.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'Trousers / Pants', 1, 0), (@q, 'Dress', 0, 1), (@q, 'T-shirt', 0, 2), (@q, 'Sweater', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What warm outer garment is worn outdoors to protect against cold weather or rain?', 'A coat is a warm outer garment worn outdoors against cold weather or rain.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'Coat', 1, 0), (@q, 'Shorts', 0, 1), (@q, 'Blouse', 0, 2), (@q, 'Swimsuit', 0, 3);

-- =====================================================================
-- Lesson 5: Home Vocabulary
-- =====================================================================
INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'Which room in a house is primarily used for cooking food and preparing meals?', 'The kitchen is the room used for cooking and preparing meals.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'Bathroom', 0, 0), (@q, 'Kitchen', 1, 1), (@q, 'Bedroom', 0, 2), (@q, 'Garage', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What piece of furniture in the living room is designed for multiple people to sit comfortably together?', 'A sofa / couch seats multiple people comfortably in the living room.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'Bed', 0, 0), (@q, 'Sofa / Couch', 1, 1), (@q, 'Desk', 0, 2), (@q, 'Wardrobe', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'Which room contains a bed, nightstand, and wardrobe, and is used for sleeping and resting?', 'The bedroom contains a bed, nightstand, wardrobe, and is used for sleeping.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'Living room', 0, 0), (@q, 'Bedroom', 1, 1), (@q, 'Kitchen', 0, 2), (@q, 'Dining room', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What household fixture is found in the bathroom and is used for washing your entire body while standing?', 'A shower is used in the bathroom for washing the entire body while standing.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'Sink', 0, 0), (@q, 'Shower', 1, 1), (@q, 'Toilet', 0, 2), (@q, 'Mirror', 0, 3);