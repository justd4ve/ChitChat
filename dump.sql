--
-- PostgreSQL database dump
--

SET statement_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SET check_function_bodies = false;
SET client_min_messages = warning;

SET search_path = public, pg_catalog;

--
-- Name: gender; Type: TYPE; Schema: public; Owner: xdvorak
--

CREATE TYPE gender AS ENUM (
    'male',
    'female'
);


ALTER TYPE public.gender OWNER TO xdvorak;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- Name: in_room; Type: TABLE; Schema: public; Owner: xdvorak; Tablespace: 
--

CREATE TABLE in_room (
    id_users integer NOT NULL,
    id_rooms integer NOT NULL,
    last_message timestamp without time zone NOT NULL,
    entered timestamp without time zone NOT NULL,
    last_entry timestamp without time zone NOT NULL
);


ALTER TABLE public.in_room OWNER TO xdvorak;

--
-- Name: messages_id_messages_seq; Type: SEQUENCE; Schema: public; Owner: xdvorak
--

CREATE SEQUENCE messages_id_messages_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.messages_id_messages_seq OWNER TO xdvorak;

--
-- Name: messages_id_messages_seq; Type: SEQUENCE SET; Schema: public; Owner: xdvorak
--

SELECT pg_catalog.setval('messages_id_messages_seq', 17, true);


--
-- Name: messages; Type: TABLE; Schema: public; Owner: xdvorak; Tablespace: 
--

CREATE TABLE messages (
    id_messages integer DEFAULT nextval('messages_id_messages_seq'::regclass) NOT NULL,
    id_rooms integer NOT NULL,
    id_users_from integer NOT NULL,
    id_users_to integer,
    created timestamp without time zone NOT NULL,
    message character varying(255) NOT NULL
);


ALTER TABLE public.messages OWNER TO xdvorak;

--
-- Name: persons_id_seq; Type: SEQUENCE; Schema: public; Owner: xdvorak
--

CREATE SEQUENCE persons_id_seq
    START WITH 3
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.persons_id_seq OWNER TO xdvorak;

--
-- Name: persons_id_seq; Type: SEQUENCE SET; Schema: public; Owner: xdvorak
--

SELECT pg_catalog.setval('persons_id_seq', 3, false);


--
-- Name: room_kick; Type: TABLE; Schema: public; Owner: xdvorak; Tablespace: 
--

CREATE TABLE room_kick (
    id_users integer NOT NULL,
    id_rooms integer NOT NULL,
    created timestamp without time zone NOT NULL
);


ALTER TABLE public.room_kick OWNER TO xdvorak;

--
-- Name: rooms_id_rooms_seq; Type: SEQUENCE; Schema: public; Owner: xdvorak
--

CREATE SEQUENCE rooms_id_rooms_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.rooms_id_rooms_seq OWNER TO xdvorak;

--
-- Name: rooms_id_rooms_seq; Type: SEQUENCE SET; Schema: public; Owner: xdvorak
--

SELECT pg_catalog.setval('rooms_id_rooms_seq', 119, true);


--
-- Name: rooms; Type: TABLE; Schema: public; Owner: xdvorak; Tablespace: 
--

CREATE TABLE rooms (
    id_rooms integer DEFAULT nextval('rooms_id_rooms_seq'::regclass) NOT NULL,
    created timestamp without time zone NOT NULL,
    title character varying(100) NOT NULL,
    id_users_owner integer NOT NULL,
    lock boolean DEFAULT false NOT NULL,
    language character varying(2)
);


ALTER TABLE public.rooms OWNER TO xdvorak;

--
-- Name: ui; Type: TABLE; Schema: public; Owner: xdvorak; Tablespace: 
--

CREATE TABLE ui (
    id_ui integer NOT NULL,
    element character varying(20),
    text_en character varying(50) NOT NULL,
    text_cz character varying(50),
    component character varying(20)
);


ALTER TABLE public.ui OWNER TO xdvorak;

--
-- Name: ui_id_ui_seq; Type: SEQUENCE; Schema: public; Owner: xdvorak
--

CREATE SEQUENCE ui_id_ui_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.ui_id_ui_seq OWNER TO xdvorak;

--
-- Name: ui_id_ui_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: xdvorak
--

ALTER SEQUENCE ui_id_ui_seq OWNED BY ui.id_ui;


--
-- Name: ui_id_ui_seq; Type: SEQUENCE SET; Schema: public; Owner: xdvorak
--

SELECT pg_catalog.setval('ui_id_ui_seq', 70, true);


--
-- Name: users_id_users_seq; Type: SEQUENCE; Schema: public; Owner: xdvorak
--

CREATE SEQUENCE users_id_users_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.users_id_users_seq OWNER TO xdvorak;

--
-- Name: users_id_users_seq; Type: SEQUENCE SET; Schema: public; Owner: xdvorak
--

SELECT pg_catalog.setval('users_id_users_seq', 50, true);


--
-- Name: users; Type: TABLE; Schema: public; Owner: xdvorak; Tablespace: 
--

CREATE TABLE users (
    id_users integer DEFAULT nextval('users_id_users_seq'::regclass) NOT NULL,
    login character varying(100) NOT NULL,
    email character varying(255) NOT NULL,
    password character varying(255) NOT NULL,
    name character varying(100) NOT NULL,
    surname character varying(100) NOT NULL,
    gender gender NOT NULL,
    registered timestamp without time zone NOT NULL,
    role character varying(20) DEFAULT "current_user"() NOT NULL
);


ALTER TABLE public.users OWNER TO xdvorak;

--
-- Name: id_ui; Type: DEFAULT; Schema: public; Owner: xdvorak
--

ALTER TABLE ONLY ui ALTER COLUMN id_ui SET DEFAULT nextval('ui_id_ui_seq'::regclass);


--
-- Data for Name: in_room; Type: TABLE DATA; Schema: public; Owner: xdvorak
--

INSERT INTO in_room VALUES (45, 117, '2018-06-06 16:16:35.214803', '2018-06-06 16:16:31.7219', '2018-06-06 16:16:31.88849');
INSERT INTO in_room VALUES (48, 117, '2018-06-06 16:16:28.880343', '2018-06-06 16:16:25.629202', '2018-06-06 16:18:09.625636');
INSERT INTO in_room VALUES (44, 117, '2018-06-06 16:03:10.903295', '2018-06-06 16:03:10.903295', '2018-06-06 16:21:02.31274');
INSERT INTO in_room VALUES (45, 119, '2018-06-06 17:01:28.439828', '2018-06-06 17:01:28.439828', '2018-06-06 17:01:28.439828');
INSERT INTO in_room VALUES (44, 119, '2018-06-06 17:01:38.204286', '2018-06-06 16:37:46.120806', '2018-06-06 17:01:12.313359');


--
-- Data for Name: messages; Type: TABLE DATA; Schema: public; Owner: xdvorak
--

INSERT INTO messages VALUES (235, 119, 44, NULL, '2018-06-06 17:01:17.95988', 'ahoj');
INSERT INTO messages VALUES (236, 119, 44, 45, '2018-06-06 17:01:38.162621', 'kjh');
INSERT INTO messages VALUES (233, 117, 48, NULL, '2018-06-06 16:16:28.876158', 'bagoidfkjgsd');
INSERT INTO messages VALUES (234, 117, 45, NULL, '2018-06-06 16:16:35.210192', 'saaaaaaaaaaaaaaaaa');


--
-- Data for Name: room_kick; Type: TABLE DATA; Schema: public; Owner: xdvorak
--



--
-- Data for Name: rooms; Type: TABLE DATA; Schema: public; Owner: xdvorak
--

INSERT INTO rooms VALUES (117, '2018-06-06 16:03:10.866228', 'test2', 44, false, 'en');
INSERT INTO rooms VALUES (119, '2018-06-06 16:37:46.104031', '☎☎☎', 44, false, NULL);


--
-- Data for Name: ui; Type: TABLE DATA; Schema: public; Owner: xdvorak
--

INSERT INTO ui VALUES (52, 'alertNames', 'Identical names!', 'Shodné názvy!', 'InLayout');
INSERT INTO ui VALUES (53, 'alertEmpty', 'Missing name!', 'Chybí název!', 'InLayout');
INSERT INTO ui VALUES (54, 'alertLeave', 'Do you really want to leave this room?', 'Opravdu chcete opustit tuto místnost?', 'InLayout');
INSERT INTO ui VALUES (7, 'gender', 'Gender', 'Pohlaví', 'Register');
INSERT INTO ui VALUES (55, 'notAdmin', 'Unauthorized', 'Nejsi admin', 'InLayout');
INSERT INTO ui VALUES (6, 'pass', 'Password', 'Heslo', 'Register');
INSERT INTO ui VALUES (4, 'langSelect', 'Language', 'Jazyk', 'PreLayout');
INSERT INTO ui VALUES (2, 'register', 'Register', 'Registrace', 'PreLayout');
INSERT INTO ui VALUES (56, 'locked', 'Locked', 'Uzamčeno', 'InLayout');
INSERT INTO ui VALUES (1, 'login', 'Login', 'Přihlášení', 'PreLayout');
INSERT INTO ui VALUES (57, 'addUser', 'Add user', 'Přidat uživatele', 'InLayout');
INSERT INTO ui VALUES (9, 'pass', 'Password', 'Heslo', 'Login');
INSERT INTO ui VALUES (58, 'lock', 'Lock', 'Zamknout', 'InLayout');
INSERT INTO ui VALUES (59, 'unlock', 'Unlock', 'Odemknout', 'InLayout');
INSERT INTO ui VALUES (60, 'unlocked', 'Unlocked', 'Odemčeno', 'InLayout');
INSERT INTO ui VALUES (8, 'login', 'Log in', 'Přihlášení', 'Login');
INSERT INTO ui VALUES (10, 'login', 'Login', 'Login', 'Login');
INSERT INTO ui VALUES (11, 'name', 'Name', 'Jméno', 'Register');
INSERT INTO ui VALUES (12, 'surname', 'Surname', 'Příjmení', 'Register');
INSERT INTO ui VALUES (13, 'title', 'Registration', 'Registrace', 'Register');
INSERT INTO ui VALUES (15, 'gender', 'Female', 'Žena', 'Register');
INSERT INTO ui VALUES (14, 'gender', 'Male', 'Muž', 'Register');
INSERT INTO ui VALUES (16, 'button', 'Register', 'Registrovat', 'Register');
INSERT INTO ui VALUES (17, 'login', 'Log in', 'Přihlásit se', 'Login');
INSERT INTO ui VALUES (62, 'search', 'Search', 'Hledej', 'InLayout');
INSERT INTO ui VALUES (63, 'titleUser', 'login (name surname)', 'login (jméno příjmení)', 'InLayout');
INSERT INTO ui VALUES (3, 'rooms_title', 'List of rooms', 'Výpis místností', 'InLayout');
INSERT INTO ui VALUES (61, 'notAdmin', 'You are not an administrator', 'Nejsi administrátorem', 'InLayout');
INSERT INTO ui VALUES (19, 'headerTitle', 'Name', 'Název', 'InLayout');
INSERT INTO ui VALUES (20, 'headerAdmin', 'Admin', 'Administrátor', 'InLayout');
INSERT INTO ui VALUES (21, 'newRoomTitle', 'Add new room', 'Přidat novou místnost', 'InLayout');
INSERT INTO ui VALUES (22, 'newRoomLang', 'Language', 'Jazyk', 'InLayout');
INSERT INTO ui VALUES (23, 'newRoomBtn', 'Add room', 'Přidat místnost', 'InLayout');
INSERT INTO ui VALUES (24, 'profileTitle', 'User profile:', 'Profil uživatele', 'InLayout');
INSERT INTO ui VALUES (25, 'id', 'User Id', 'Id uživatele', 'InLayout');
INSERT INTO ui VALUES (26, 'registered', 'Registered', 'Zaregistrován dne', 'InLayout');
INSERT INTO ui VALUES (27, 'name', 'Name', 'Jméno', 'InLayout');
INSERT INTO ui VALUES (28, 'surname', 'Surname', 'Příjmení', 'InLayout');
INSERT INTO ui VALUES (29, 'gender', 'Gender', 'Pohlaví', 'InLayout');
INSERT INTO ui VALUES (30, 'genderOpt', 'Male', 'Muž', 'InLayout');
INSERT INTO ui VALUES (31, 'genderOpt', 'Female', 'Žena', 'InLayout');
INSERT INTO ui VALUES (32, 'profileBtn', 'Save Changes', 'Uložit změny', 'InLayout');
INSERT INTO ui VALUES (33, 'logoutBtn', 'Log out', 'Odhlásit se', 'InLayout');
INSERT INTO ui VALUES (34, 'changesAlert', 'Changes saved successfully', 'Změny úspěšně uloženy', 'InLayout');
INSERT INTO ui VALUES (35, 'navProfile', 'Profile', 'Profil', 'InLayout');
INSERT INTO ui VALUES (36, 'navRooms', 'Rooms', 'Místnosti', 'InLayout');
INSERT INTO ui VALUES (37, 'navMyRooms', 'My rooms', 'Moje místnosti', 'InLayout');
INSERT INTO ui VALUES (38, 'navClose', 'Close', 'Zavřít', 'InLayout');
INSERT INTO ui VALUES (39, 'roomTitle', 'Room', 'Místnost', 'InLayout');
INSERT INTO ui VALUES (40, 'sendBtn', 'Send', 'Odeslat', 'InLayout');
INSERT INTO ui VALUES (41, 'msgPlaceholder', 'Message text', 'Text zprávy', 'InLayout');
INSERT INTO ui VALUES (64, 'added', 'Added', 'Přidáno', 'InLayout');
INSERT INTO ui VALUES (18, 'headerLang', 'Language', 'Jazyk', 'InLayout');
INSERT INTO ui VALUES (42, 'genderUndef', 'Undefined', 'Nezadáno', 'InLayout');
INSERT INTO ui VALUES (43, 'userRooms', 'Total rooms', 'Místností celkem', 'InLayout');
INSERT INTO ui VALUES (44, 'userAdminRooms', 'Admin positions', 'Pozic administrátora', 'InLayout');
INSERT INTO ui VALUES (45, 'userList', 'List of users', 'Seznam uživatelů', 'InLayout');
INSERT INTO ui VALUES (46, 'renameBtn', 'Rename', 'Přejmenovat', 'InLayout');
INSERT INTO ui VALUES (47, 'lockBtn', 'Lock', 'Uzamknout', 'InLayout');
INSERT INTO ui VALUES (48, 'lockBtn', 'Unlock', 'Odemknout', 'InLayout');
INSERT INTO ui VALUES (49, 'leaveBtn', 'Leave', 'Opustit', 'InLayout');
INSERT INTO ui VALUES (50, 'renameTitle', 'Rename room', 'Přejmenovat místnost', 'InLayout');
INSERT INTO ui VALUES (51, 'renameText', 'Name', 'Název', 'InLayout');
INSERT INTO ui VALUES (65, 'toAll', 'To all', 'Všem', 'InLayout');
INSERT INTO ui VALUES (66, 'roomLocked', 'The room is locked', 'Místnost je uzamčená', 'InLayout');
INSERT INTO ui VALUES (67, 'registErr', 'Registration error', 'Chyba registrace', 'Register');
INSERT INTO ui VALUES (68, 'editErr', 'Editation error', 'Chyba editace', 'InLayout');
INSERT INTO ui VALUES (69, 'loginErr', 'Wrong login or password', 'Špatné přihlašovací údaje', 'Login');
INSERT INTO ui VALUES (70, 'roomKick', 'You were kicked out of the room', 'Byl jsi vyhozen z místnosti', 'InLayout');


--
-- Data for Name: users; Type: TABLE DATA; Schema: public; Owner: xdvorak
--

INSERT INTO users VALUES (48, 'lysek', 'jiri.lysek@mendelu.cz', '$2y$10$/y/WztujIX5nvCnUJHwt9.JvYianEobrK8qZfWKntteIcbYH.7672', 'Jiri', 'Lysek', 'male', '2018-06-06 16:09:08.033032', 'xdvorak');
INSERT INTO users VALUES (44, 'test1', 'test@test.cz', '$2y$10$phNKMCFI7z6qjcifct.PK.DSPnxM5AmfMo3Qyc0ewE1p2pTBpXpb6', 'test', 'test', 'male', '2018-06-05 13:22:41.72687', 'xdvorak');
INSERT INTO users VALUES (45, 'test2', 'test@test.com', '$2y$10$SdL23H8HfzHaqnrDs4HxBePRdYJgJsI32RDoG/uN91PgnKVwAGtua', 'test', 'test', 'female', '2018-06-05 13:22:58.510457', 'xdvorak');


--
-- Name: in_room_id_users_id_rooms; Type: CONSTRAINT; Schema: public; Owner: xdvorak; Tablespace: 
--

ALTER TABLE ONLY in_room
    ADD CONSTRAINT in_room_id_users_id_rooms PRIMARY KEY (id_users, id_rooms);


--
-- Name: messages_id_messages; Type: CONSTRAINT; Schema: public; Owner: xdvorak; Tablespace: 
--

ALTER TABLE ONLY messages
    ADD CONSTRAINT messages_id_messages PRIMARY KEY (id_messages);


--
-- Name: room_kick_id_users_id_rooms; Type: CONSTRAINT; Schema: public; Owner: xdvorak; Tablespace: 
--

ALTER TABLE ONLY room_kick
    ADD CONSTRAINT room_kick_id_users_id_rooms PRIMARY KEY (id_users, id_rooms);


--
-- Name: rooms_id_rooms; Type: CONSTRAINT; Schema: public; Owner: xdvorak; Tablespace: 
--

ALTER TABLE ONLY rooms
    ADD CONSTRAINT rooms_id_rooms PRIMARY KEY (id_rooms);


--
-- Name: ui_id_ui; Type: CONSTRAINT; Schema: public; Owner: xdvorak; Tablespace: 
--

ALTER TABLE ONLY ui
    ADD CONSTRAINT ui_id_ui PRIMARY KEY (id_ui);


--
-- Name: users_email; Type: CONSTRAINT; Schema: public; Owner: xdvorak; Tablespace: 
--

ALTER TABLE ONLY users
    ADD CONSTRAINT users_email UNIQUE (email);


--
-- Name: users_id_users; Type: CONSTRAINT; Schema: public; Owner: xdvorak; Tablespace: 
--

ALTER TABLE ONLY users
    ADD CONSTRAINT users_id_users PRIMARY KEY (id_users);


--
-- Name: users_login; Type: CONSTRAINT; Schema: public; Owner: xdvorak; Tablespace: 
--

ALTER TABLE ONLY users
    ADD CONSTRAINT users_login UNIQUE (login);


--
-- Name: in_room_id_rooms; Type: INDEX; Schema: public; Owner: xdvorak; Tablespace: 
--

CREATE INDEX in_room_id_rooms ON in_room USING btree (id_rooms);


--
-- Name: in_room_id_users; Type: INDEX; Schema: public; Owner: xdvorak; Tablespace: 
--

CREATE INDEX in_room_id_users ON in_room USING btree (id_users);


--
-- Name: messages_id_rooms; Type: INDEX; Schema: public; Owner: xdvorak; Tablespace: 
--

CREATE INDEX messages_id_rooms ON messages USING btree (id_rooms);


--
-- Name: messages_id_users_from; Type: INDEX; Schema: public; Owner: xdvorak; Tablespace: 
--

CREATE INDEX messages_id_users_from ON messages USING btree (id_users_from);


--
-- Name: messages_id_users_to; Type: INDEX; Schema: public; Owner: xdvorak; Tablespace: 
--

CREATE INDEX messages_id_users_to ON messages USING btree (id_users_to);


--
-- Name: room_kick_id_rooms; Type: INDEX; Schema: public; Owner: xdvorak; Tablespace: 
--

CREATE INDEX room_kick_id_rooms ON room_kick USING btree (id_rooms);


--
-- Name: rooms_id_users_owner; Type: INDEX; Schema: public; Owner: xdvorak; Tablespace: 
--

CREATE INDEX rooms_id_users_owner ON rooms USING btree (id_users_owner);


--
-- Name: in_room_id_rooms_fkey; Type: FK CONSTRAINT; Schema: public; Owner: xdvorak
--

ALTER TABLE ONLY in_room
    ADD CONSTRAINT in_room_id_rooms_fkey FOREIGN KEY (id_rooms) REFERENCES rooms(id_rooms) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: in_room_id_users_fkey; Type: FK CONSTRAINT; Schema: public; Owner: xdvorak
--

ALTER TABLE ONLY in_room
    ADD CONSTRAINT in_room_id_users_fkey FOREIGN KEY (id_users) REFERENCES users(id_users) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: messages_id_rooms_fkey; Type: FK CONSTRAINT; Schema: public; Owner: xdvorak
--

ALTER TABLE ONLY messages
    ADD CONSTRAINT messages_id_rooms_fkey FOREIGN KEY (id_rooms) REFERENCES rooms(id_rooms) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: messages_id_users_from_fkey; Type: FK CONSTRAINT; Schema: public; Owner: xdvorak
--

ALTER TABLE ONLY messages
    ADD CONSTRAINT messages_id_users_from_fkey FOREIGN KEY (id_users_from) REFERENCES users(id_users) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: messages_id_users_to_fkey; Type: FK CONSTRAINT; Schema: public; Owner: xdvorak
--

ALTER TABLE ONLY messages
    ADD CONSTRAINT messages_id_users_to_fkey FOREIGN KEY (id_users_to) REFERENCES users(id_users) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: room_kick_id_rooms_fkey; Type: FK CONSTRAINT; Schema: public; Owner: xdvorak
--

ALTER TABLE ONLY room_kick
    ADD CONSTRAINT room_kick_id_rooms_fkey FOREIGN KEY (id_rooms) REFERENCES rooms(id_rooms) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: room_kick_id_users_fkey; Type: FK CONSTRAINT; Schema: public; Owner: xdvorak
--

ALTER TABLE ONLY room_kick
    ADD CONSTRAINT room_kick_id_users_fkey FOREIGN KEY (id_users) REFERENCES users(id_users) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: rooms_id_users_owner_fkey; Type: FK CONSTRAINT; Schema: public; Owner: xdvorak
--

ALTER TABLE ONLY rooms
    ADD CONSTRAINT rooms_id_users_owner_fkey FOREIGN KEY (id_users_owner) REFERENCES users(id_users) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: public; Type: ACL; Schema: -; Owner: postgres
--

REVOKE ALL ON SCHEMA public FROM PUBLIC;
REVOKE ALL ON SCHEMA public FROM postgres;
GRANT ALL ON SCHEMA public TO postgres;
GRANT ALL ON SCHEMA public TO PUBLIC;


--
-- PostgreSQL database dump complete
--

