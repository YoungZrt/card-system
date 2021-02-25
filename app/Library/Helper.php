<?php
namespace App\Library; use Hashids\Hashids; class Helper { public static function getMysqlDate($spc5ea73 = 0) { return date('Y-m-d', time() + $spc5ea73 * 24 * 3600); } public static function getIP() { if (isset($_SERVER)) { if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) { $sp6c3215 = $_SERVER['HTTP_X_FORWARDED_FOR']; } else { if (isset($_SERVER['HTTP_CLIENT_IP'])) { $sp6c3215 = $_SERVER['HTTP_CLIENT_IP']; } else { $sp6c3215 = @$_SERVER['REMOTE_ADDR']; } } } else { if (getenv('HTTP_X_FORWARDED_FOR')) { $sp6c3215 = getenv('HTTP_X_FORWARDED_FOR'); } else { if (getenv('HTTP_CLIENT_IP')) { $sp6c3215 = getenv('HTTP_CLIENT_IP'); } else { $sp6c3215 = getenv('REMOTE_ADDR'); } } } if (strpos($sp6c3215, ',') !== FALSE) { $spcd2530 = explode(',', $sp6c3215); return $spcd2530[0]; } return $sp6c3215; } public static function getClientIP() { if (isset($_SERVER)) { $sp6c3215 = $_SERVER['REMOTE_ADDR']; } else { $sp6c3215 = getenv('REMOTE_ADDR'); } if (strpos($sp6c3215, ',') !== FALSE) { $spcd2530 = explode(',', $sp6c3215); return $spcd2530[0]; } return $sp6c3215; } public static function filterWords($sp5a6390, $sp82333a) { if (!$sp5a6390) { return false; } if (!is_array($sp82333a)) { $sp82333a = explode('|', $sp82333a); } foreach ($sp82333a as $sp1ac357) { if ($sp1ac357 && strpos($sp5a6390, $sp1ac357) !== FALSE) { return $sp1ac357; } } return false; } public static function is_idcard($sp0e72a6) { if (strlen($sp0e72a6) == 18) { return self::idcard_checksum18($sp0e72a6); } elseif (strlen($sp0e72a6) == 15) { $sp0e72a6 = self::idcard_15to18($sp0e72a6); return self::idcard_checksum18($sp0e72a6); } else { return false; } } private static function idcard_verify_number($sp04a747) { if (strlen($sp04a747) != 17) { return false; } $sp0f1527 = array(7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2); $spb7c2c6 = array('1', '0', 'X', '9', '8', '7', '6', '5', '4', '3', '2'); $sp697b85 = 0; for ($sp02b863 = 0; $sp02b863 < strlen($sp04a747); $sp02b863++) { $sp697b85 += substr($sp04a747, $sp02b863, 1) * $sp0f1527[$sp02b863]; } $spa7903f = $sp697b85 % 11; $spdab008 = $spb7c2c6[$spa7903f]; return $spdab008; } private static function idcard_15to18($sp3d30ac) { if (strlen($sp3d30ac) != 15) { return false; } else { if (array_search(substr($sp3d30ac, 12, 3), array('996', '997', '998', '999')) !== false) { $sp3d30ac = substr($sp3d30ac, 0, 6) . '18' . substr($sp3d30ac, 6, 9); } else { $sp3d30ac = substr($sp3d30ac, 0, 6) . '19' . substr($sp3d30ac, 6, 9); } } $sp3d30ac = $sp3d30ac . self::idcard_verify_number($sp3d30ac); return $sp3d30ac; } private static function idcard_checksum18($sp3d30ac) { if (strlen($sp3d30ac) != 18) { return false; } $sp04a747 = substr($sp3d30ac, 0, 17); if (self::idcard_verify_number($sp04a747) != strtoupper(substr($sp3d30ac, 17, 1))) { return false; } else { return true; } } public static function str_between($sp5a6390, $sp776c06, $specfc07) { $sp60366a = strpos($sp5a6390, $sp776c06); if ($sp60366a === false) { return ''; } $spac1b85 = strpos($sp5a6390, $specfc07, $sp60366a + strlen($sp776c06)); if ($spac1b85 === false || $sp60366a >= $spac1b85) { return ''; } $spd74a8e = strlen($sp776c06); $sp571ce3 = substr($sp5a6390, $sp60366a + $spd74a8e, $spac1b85 - $sp60366a - $spd74a8e); return $sp571ce3; } public static function str_between_longest($sp5a6390, $sp776c06, $specfc07) { $sp60366a = strpos($sp5a6390, $sp776c06); if ($sp60366a === false) { return ''; } $spac1b85 = strrpos($sp5a6390, $specfc07, $sp60366a + strlen($sp776c06)); if ($spac1b85 === false || $sp60366a >= $spac1b85) { return ''; } $spd74a8e = strlen($sp776c06); $sp571ce3 = substr($sp5a6390, $sp60366a + $spd74a8e, $spac1b85 - $sp60366a - $spd74a8e); return $sp571ce3; } public static function format_url($sp783cd0) { if (!strlen($sp783cd0)) { return $sp783cd0; } if (!starts_with($sp783cd0, 'http://') && !starts_with($sp783cd0, 'https://')) { $sp783cd0 = 'http://' . $sp783cd0; } while (ends_with($sp783cd0, '/')) { $sp783cd0 = substr($sp783cd0, 0, -1); } return $sp783cd0; } public static function lite_hash($sp5a6390) { $sp514127 = crc32((string) $sp5a6390); if ($sp514127 < 0) { $sp514127 &= 1 << 7; } return $sp514127; } const ID_TYPE_USER = 0; const ID_TYPE_CATEGORY = 1; const ID_TYPE_PRODUCT = 2; const ID_TYPE_AFFILIATE = 3; public static function id_encode($spe8e527, $sp2add78, ...$sp772c0d) { $sp07ab4b = new Hashids(config('app.key'), 8, 'abcdefghijklmnopqrstuvwxyz1234567890'); return @$sp07ab4b->encode(self::lite_hash($spe8e527), $spe8e527, self::lite_hash($sp2add78), $sp2add78, ...$sp772c0d); } public static function id_decode($sp4d316b, $sp2add78, &$sp4048e3 = false) { if (strlen($sp4d316b) < 8) { $sp07ab4b = new Hashids(config('app.key')); if ($sp2add78 === self::ID_TYPE_USER) { return intval(@$sp07ab4b->decodeHex($sp4d316b)); } else { return intval(@$sp07ab4b->decode($sp4d316b)[0]); } } $sp07ab4b = new Hashids(config('app.key'), 8, 'abcdefghijklmnopqrstuvwxyz1234567890'); $sp4048e3 = @$sp07ab4b->decode($sp4d316b) ?? array(); return intval($sp4048e3[1]); } public static function is_mobile() { if (isset($_SERVER['HTTP_USER_AGENT'])) { if (preg_match('/(iPhone|iPod|Android|ios|SymbianOS|Windows Phone)/i', $_SERVER['HTTP_USER_AGENT'])) { return true; } } return false; } public static function b1_rand_background() { if (self::is_mobile()) { $spd24ad7 = array('//ww2.sinaimg.cn/large/ac1a0c4agy1ftxpgyq8n5j20u01hcne2.jpg', '//ww2.sinaimg.cn/large/ac1a0c4agy1ftxpfyjbd0j20u01hcte2.jpg', '//ww2.sinaimg.cn/large/ac1a0c4agy1ftxpw3b5mkj20u01hcnfh.jpg', '//ww2.sinaimg.cn/large/ac1a0c4agy1ftxoybkicbj20u01hc7de.jpg', '//ww2.sinaimg.cn/large/ac1a0c4agy1ftxpes8rmmj20u01hctn7.jpg', '//ww2.sinaimg.cn/large/ac1a0c4agy1ftxp8ond6gj20u01hctji.jpg', '//ww2.sinaimg.cn/large/ac1a0c4agy1ftxp4ljhhvj20u01hck0r.jpg', '//ww2.sinaimg.cn/large/ac1a0c4agy1ftxpstrwnsj20u01hc7he.jpg', '//ww2.sinaimg.cn/large/ac1a0c4agy1ftxq2a1vthj20u01hc4gs.jpg', '//ww2.sinaimg.cn/large/ac1a0c4agy1ftxpiebjztj20u01hcaom.jpg', '//ww2.sinaimg.cn/large/ac1a0c4agy1ftxow4b14kj20u01hc43x.jpg', '//ww2.sinaimg.cn/large/ac1a0c4agy1ftxohtyvgfj20u01hc7gk.jpg', '//ww2.sinaimg.cn/large/ac1a0c4agy1ftxp6vexa3j20u01hcdj3.jpg', '//ww2.sinaimg.cn/large/ac1a0c4agy1ftxqa0zhc6j20u01hc14e.jpg', '//ww2.sinaimg.cn/large/ac1a0c4agy1ftxomnbr0gj20u01hc79r.jpg', '//ww2.sinaimg.cn/large/ac1a0c4agy1ftxpx57f0sj20u01hcqmd.jpg', '//ww2.sinaimg.cn/large/ac1a0c4agy1ftxoozjilyj20u01hcgt9.jpg', '//ww2.sinaimg.cn/large/ac1a0c4agy1ftxprigfw1j20u01hcam9.jpg', '//ww2.sinaimg.cn/large/ac1a0c4agy1ftxod70fcpj20u01hcajj.jpg', '//ww2.sinaimg.cn/large/ac1a0c4agy1ftxpzb5p1tj20u01hcnca.jpg', '//ww2.sinaimg.cn/large/ac1a0c4agy1ftxozvry57j20u01hcgwo.jpg', '//ww2.sinaimg.cn/large/ac1a0c4agy1ftxpv092lfj20u01hcx1o.jpg', '//ww2.sinaimg.cn/large/ac1a0c4agy1ftxpdz6s0bj20u01hcaqj.jpg', '//ww2.sinaimg.cn/large/ac1a0c4agy1ftxoso79ayj20u01hcq9c.jpg', '//ww2.sinaimg.cn/large/ac1a0c4agy1ftxpqjrtjhj20u01hcapi.jpg'); } else { $spd24ad7 = array('//ww1.sinaimg.cn/large/ac1a0c4agy1ftz78cfrj2j21hc0u0kio.jpg', '//ww1.sinaimg.cn/large/ac1a0c4agy1ftz7qj6l3xj21hc0u0b29.jpg', '//ww1.sinaimg.cn/large/ac1a0c4agy1ft9tqa2fvpj21hc0u017a.jpg', '//ww1.sinaimg.cn/large/ac1a0c4agy1ftz71m76skj21hc0u0nnq.jpg', '//ww1.sinaimg.cn/large/ac1a0c4agy1ftz709py6fj21hc0u0wx2.jpg', '//ww1.sinaimg.cn/large/ac1a0c4agy1ft9sgqv33lj21hc0u04qp.jpg', '//ww1.sinaimg.cn/large/ac1a0c4agy1ft9s9soh4sj21hc0u01kx.jpg', '//ww1.sinaimg.cn/large/ac1a0c4agy1ft9s9r2vkzj21hc0u0x4e.jpg', '//ww1.sinaimg.cn/large/ac1a0c4agy1ftz7etbcs8j21hc0u07p3.jpg', '//ww1.sinaimg.cn/large/ac1a0c4agy1ft9sgn1bluj21hc0u0kiy.jpg', '//ww1.sinaimg.cn/large/ac1a0c4agy1ftz7r6tmv1j21hc0u0anj.jpg', '//ww1.sinaimg.cn/large/ac1a0c4agy1ftz7c4h0xzj21hc0u01kx.jpg', '//ww1.sinaimg.cn/large/ac1a0c4agy1ft9tq7uypvj21hc0u01be.jpg', '//ww1.sinaimg.cn/large/ac1a0c4agy1fwr4pjgbncj21hc0u0kjl.jpg', '//ww1.sinaimg.cn/large/ac1a0c4agy1ftz7i6u1gxj21hc0u0tyk.jpg', '//ww1.sinaimg.cn/large/ac1a0c4agy1fwr4s0fb2tj21hc0u01ky.jpg', '//ww1.sinaimg.cn/large/ac1a0c4agy1ftz72wkr9dj21hc0u0h1r.jpg', '//ww1.sinaimg.cn/large/ac1a0c4agy1ftz7tj5ohrj21hc0u0qnp.jpg', '//ww1.sinaimg.cn/large/ac1a0c4agy1ft9sgp23zbj21hc0u0txl.jpg', '//ww1.sinaimg.cn/large/ac1a0c4agy1ftz7l9dcokj21hc0u0k9k.jpg', '//ww1.sinaimg.cn/large/ac1a0c4agy1fwr4lvumu1j21hc0u0x6p.jpg', '//ww1.sinaimg.cn/large/ac1a0c4agy1ftz7alxyhnj21hc0u0nkh.jpg', '//ww1.sinaimg.cn/large/ac1a0c4agy1ftz799gvb3j21hc0u0qdt.jpg'); } return $spd24ad7[rand(0, count($spd24ad7) - 1)]; } public static function isWakePassword($sp1f7a77) { $sp63a4eb = array('123456', 'password', '12345678', 'qwerty', '123456789', '12345', '1234', '111111', '1234567', 'dragon', '123123', 'baseball', 'abc123', 'football', 'monkey', 'letmein', '696969', 'shadow', 'master', '666666', 'qwertyuiop', '123321', 'mustang', '1234567890', 'michael', '654321', 'pussy', 'superman', '1qaz2wsx', '7777777', 'fuckyou', '121212', '000000', 'qazwsx', '123qwe', 'killer', 'trustno1', 'jordan', 'jennifer', 'zxcvbnm', 'asdfgh', 'hunter', 'buster', 'soccer', 'harley', 'batman', 'andrew', 'tigger', 'sunshine', 'iloveyou', 'fuckme', '2000', 'charlie', 'robert', 'thomas', 'hockey', 'ranger', 'daniel', 'starwars', 'klaster', '112233', 'george', 'asshole', 'computer', 'michelle', 'jessica', 'pepper', '1111', 'zxcvbn', '555555', '11111111', '131313', 'freedom', '777777', 'pass', 'fuck', 'maggie', '159753', 'aaaaaa', 'ginger', 'princess', 'joshua', 'cheese', 'amanda', 'summer', 'love', 'ashley', '6969', 'nicole', 'chelsea', 'biteme', 'matthew', 'access', 'yankees', '987654321', 'dallas', 'austin', 'thunder', 'taylor', 'matrix', 'minecraft'); return in_array($sp1f7a77, $sp63a4eb); } }