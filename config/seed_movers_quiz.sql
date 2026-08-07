-- =====================================================================
-- Seed script: Movers Module Quiz (20 questions)
-- Run once in phpMyAdmin or MySQL CLI.
--   SET @module_id = 6;  (6 = Movers) -- edit if needed.
-- =====================================================================

SET @module_id = 6;  -- <-- Movers module

-- 1. Create the quiz
INSERT INTO quizzes (module_id, quiz_title, passing_score, question_limit, time_limit, random_questions, random_answers)
VALUES (@module_id, 'Movers Module Quiz', 70, 100, 0, 1, 1);

SET @quiz_id = LAST_INSERT_ID();

-- =====================================================================
-- Lesson 1: Sports and Leisure
-- =====================================================================
INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'Which sport involves hitting a small rubber ball against a wall using a racket in an indoor court?', 'Squash is played in a small indoor court by hitting a rubber ball against the four walls.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'Cricket', 0, 0), (@q, 'Squash', 1, 1), (@q, 'Rugby', 0, 2), (@q, 'Surfing', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What equipment is essential when playing table tennis (ping-pong)?', 'Table tennis is played with a paddle (bat) and a lightweight plastic ball.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'Bat and shuttlecock', 0, 0), (@q, 'Paddle and lightweight ball', 1, 1), (@q, 'Club and tee', 0, 2), (@q, 'Stick and puck', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'Which leisure activity involves moving through water using your arms and legs without touching the bottom?', 'Swimming is moving through water using your arms and legs while the bottom is not touched.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'Cycling', 0, 0), (@q, 'Swimming', 1, 1), (@q, 'Hiking', 0, 2), (@q, 'Skiing', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'In which sport do players try to score points by throwing a ball through an elevated hoop?', 'In basketball players shoot a ball through an elevated hoop to score points.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'Basketball', 1, 0), (@q, 'Volleyball', 0, 1), (@q, 'Baseball', 0, 2), (@q, 'Soccer', 0, 3);

-- =====================================================================
-- Lesson 2: Places and Directions
-- =====================================================================
INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'If you need to catch a train to travel to another city, which facility should you go to?', 'A railway station is the facility where you board trains.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'Bus stop', 0, 0), (@q, 'Railway station', 1, 1), (@q, 'Airport', 0, 2), (@q, 'Harbor', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'When giving directions, what phrase should you use if you want someone to change their path to the opposite direction?', '"Turn around" means to change direction and face the opposite way.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'Turn around', 1, 0), (@q, 'Go straight ahead', 0, 1), (@q, 'Cross the street', 0, 2), (@q, 'Take a shortcut', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'Which public building provides books, study spaces, and digital media for community members to borrow?', 'A library lets people borrow books and provides study spaces and digital media.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'Post Office', 0, 0), (@q, 'Library', 1, 1), (@q, 'City Hall', 0, 2), (@q, 'Museum', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What directional term describes a location that is directly to your left side?', '"Left" directly describes the side of a person facing you.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'East', 0, 0), (@q, 'West', 0, 1), (@q, 'Left', 1, 2), (@q, 'North', 0, 3);

-- =====================================================================
-- Lesson 3: Jobs
-- =====================================================================
INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'Which professional is primarily trained to design, build, and maintain engines, machines, and structures?', 'An engineer designs, builds and maintains engines, machines, and structures.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'Architect', 0, 0), (@q, 'Engineer', 1, 1), (@q, 'Journalist', 0, 2), (@q, 'Accountant', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What job title belongs to someone who prepares food and manages operations in a restaurant kitchen?', 'A chef prepares food and manages the operations of a kitchen.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'Waiter', 0, 0), (@q, 'Chef', 1, 1), (@q, 'Host', 0, 2), (@q, 'Cashier', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'Who is responsible for treating sick or injured animals in a clinical setting?', 'Veterinarians are animal doctors who treat sick or injured animals.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'Nurse', 0, 0), (@q, 'Veterinarian', 1, 1), (@q, 'Pharmacist', 0, 2), (@q, 'Dentist', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'Which occupation involves delivering mail, packages, and letters to residential and commercial addresses?', 'A courier or mail carrier delivers letters and packages to different addresses.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'Courier / Mail carrier', 1, 0), (@q, 'Plumber', 0, 1), (@q, 'Electrician', 0, 2), (@q, 'Carpenter', 0, 3);

-- =====================================================================
-- Lesson 4: Transport
-- =====================================================================
INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'Which form of public transport operates on fixed metal tracks embedded in city streets or dedicated lanes?', 'A tram runs on fixed metal tracks along streets or dedicated lanes.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'Tram', 1, 0), (@q, 'Helicopter', 0, 1), (@q, 'Ferry', 0, 2), (@q, 'Scooter', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What type of large commercial vehicle is primarily used for transporting heavy cargo long distances by road?', 'A truck or lorry is used to transport heavy cargo long distances by road.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'Bicycle', 0, 0), (@q, 'Truck (Lorry)', 1, 1), (@q, 'Taxi', 0, 2), (@q, 'Minivan', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'Which flying vehicle takes off and lands vertically using large overhead rotating blades?', 'A helicopter takes off and lands vertically using rotating overhead blades.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'Airplane', 0, 0), (@q, 'Helicopter', 1, 1), (@q, 'Glider', 0, 2), (@q, 'Hot-air balloon', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What water vessel is typically used to carry both passengers and vehicles across a river, lake, or narrow sea channel?', 'A ferry transports passengers and vehicles across water.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'Submarine', 0, 0), (@q, 'Yacht', 0, 1), (@q, 'Ferry', 1, 2), (@q, 'Canoe', 0, 3);

-- =====================================================================
-- Lesson 5: Health
-- =====================================================================
INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What medical professional specializes in examining teeth and treating dental hygiene issues?', 'A dentist examines teeth and treats dental hygiene issues.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'Cardiologist', 0, 0), (@q, 'Dentist', 1, 1), (@q, 'Dermatologist', 0, 2), (@q, 'Pediatrist', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'Which symptom typically indicates that a person has an elevated body temperature due to an infection?', 'A fever is an elevated body temperature usually caused by an infection.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'Fever', 1, 0), (@q, 'Sprain', 0, 1), (@q, 'Rash', 0, 2), (@q, 'Cough', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'What substance is commonly prescribed by a doctor to treat a bacterial infection?', 'An antibiotic is prescribed to treat bacterial infections.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'Vitamin supplement', 0, 0), (@q, 'Antibiotic', 1, 1), (@q, 'Antacid', 0, 2), (@q, 'Painkiller', 0, 3);

INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES
(@quiz_id, 'Which term describes the physical practice of maintaining cleanliness to prevent disease and illness?', 'Hygiene is the practice of keeping clean to prevent disease and illness.');
SET @q = LAST_INSERT_ID();
INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES
(@q, 'Hygiene', 1, 0), (@q, 'Fatigue', 0, 1), (@q, 'Therapy', 0, 2), (@q, 'Rehabilitation', 0, 3);