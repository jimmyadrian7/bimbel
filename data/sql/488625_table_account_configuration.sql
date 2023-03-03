
-- --------------------------------------------------------

--
-- Table structure for table `account_configuration`
--

CREATE TABLE `account_configuration` (
  `id` int(11) NOT NULL,
  `wa_invoice_template` varchar(255) DEFAULT NULL,
  `wa_invoice_template_language` varchar(255) DEFAULT NULL,
  `parameter` tinyint(1) NOT NULL,
  `wa_business_account_id` varchar(255) DEFAULT NULL,
  `wa_phone_number_id` varchar(255) DEFAULT NULL,
  `wa_access_token` text DEFAULT NULL,
  `mail_host` varchar(255) DEFAULT NULL,
  `mail_port` varchar(255) DEFAULT NULL,
  `mail_user` varchar(255) DEFAULT NULL,
  `mail_pass` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `account_configuration`
--

INSERT INTO `account_configuration` (`id`, `wa_invoice_template`, `wa_invoice_template_language`, `parameter`, `wa_business_account_id`, `wa_phone_number_id`, `wa_access_token`, `mail_host`, `mail_port`, `mail_user`, `mail_pass`) VALUES
(1, 'hello_world', 'en_US', 0, '103721942663000', '108612242167467', 'EAAS8ZAv6Wkz8BAC3lTfD4fFBptztppmbeSaUOEXFHq0dF9FKKZBL52jJLmiricCunqTfTfOI31ZB7oaDrv86zzrNjI4yZA0Jb4pa3nW1jlpw3Yh5G7RrsEYrxPgn1HmjNjPQjje2yDHUKZC75tZCuNBlOxqIIEQ33jTVM60ocrcZBZA1RGwmLspO2WvrbLvZAVF13bzQqEy51nQZDZD', 'smtp.gmail.com', '465', 'email.no.reply.testing@gmail.com', 'gisogqukcxtatnnc');
