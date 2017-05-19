<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if(!function_exists('isAdmin')){
	function isAdmin($address){
		return in_array($address, array(
			'xrb_3o7iocfcx1gcpa4q33ze56jmgicgct15kz4b4feu8mwxr43ju3t8cu668nei',
			'xrb_1y8ib51kuzf61mak6ojex3oor7kgntcc57rdwmt517a7nmnxpgxaxy7pgd84'
			));
	}
}

if(!function_exists('isBTC')){
	function isBTC($address){
		return in_array($address, array(
			'xrb_11h9r9dq8f1kffjp4ob89ewbofcqhun93hhjs48znfoktjrib63y5tus6fc7',
			'xrb_11z8a5gdrdtimo7fu31tyfgi5makxn1fyhbaejkpg4mjxsu76366ed6zqhn5',
			'xrb_14jyjetsh8p7jxx1of38ctsa779okt9d1pdnmtjpqiukuq8zugr3bxpxf1zu',
			'xrb_157i3hsque5btyhnkck147sto3htiq555i9kfrkah66i8uhrnhzifrps5cmm',
			'xrb_15csbzdxxpbw371yx7ygu63s79hke98tbyoyyz6peg9j9rayewofpzyw5qtf',
			'xrb_15zyhp773ai14ri9o9sywt65rfd336kzrihkr1hgychkh15hj9jros8ye19y',
			'xrb_1aridcdf53h73qp6n7urdqraaqszxcraysy6nqr19csenwffs4najhaysoz8',
			'xrb_1beyc4xfzga8bjqs9mwo6f11asockypdu7868rzx147au7n5pmahnj3c7zrc',
			'xrb_1ca68e7pgr6934g74j6anhba5hdgbeawcrbh9mi7d9ehd9gf58dow57bufh7',
			'xrb_1dns5txokxu4hdj3jnydu6bxnerd3ijzy9y6ixqmkoxtnpwuw8igodzf8os7',
			'xrb_1ej6ys9sbqzppq49geikx99gjfr5bjmuf5kfieuy1aas1c9hxcgdn5rfifyq',
			'xrb_1fexq3m3ej7tekonpm956qkohb8csgakpya1t9h6inf5hxywk87cacmp4ken',
			'xrb_1gfts18bc65ogkd95ydjd5i775zoabh81cu3451bihsrnaxps7fp3zwhkg1u',
			'xrb_1gru6tw6ab7ndqcq31ebs57w5p39e7azs17z5z9ac4w6hzi4e69wdmuqpkhd',
			'xrb_1kkh3rz38kyeodj1ezzhph3cd6abe9ct9f9h5f6tp5zfzta4sze6qgtg3oo9',
			'xrb_1mx8m6qai6kwftw1w4s44j4jcx195xcqngi7fia7rejzbhuudroxet5at1bw',
			'xrb_1mzsi9yf79yroof96uyo9f111igas1d64k5t4qehqqtme5okpjiapbe8ri6h',
			'xrb_1nz8gu5rrbe1yw4jydj9gaaeu396uz5w4gay99d77j1ajp5jgw7wdu5t1qyc',
			'xrb_1p93ni1aub9uzpnkhw9grpbxu5qoiu1imdzraq5b8ewc1zkx5wk7mox9k8qt',
			'xrb_1pgyh8exgrjxoigdjqk64ysi3a3ggnczud1kedfpm5qhu6befcjq7hmbrqtm',
			'xrb_1pj9b6a9mfzkip45qhxtrdjetgjqigeszifgm3xe6xbfb3dnwpt5j43o8sp6',
			'xrb_1pkneux7anrfpiari9d3cf8i57dsjx38gm4qxapd1dm9paxc3dkiaxfkkqf1',
			'xrb_1qspmpgbsk5jhbmqehittkz43zruqgu9cshecdsrqb6yphn15ai56oxtgh5f',
			'xrb_1rck5o87m3uowpb5pimmdeuej1hgitee3n5ux6zpda5ehtig4mooci4uemzt',
			'xrb_1rgffjigjti5jqneomfiowns7zotrncacj7epgfnyrgoygkehr5ndxj3ywzc',
			'xrb_1s484k471eq8e68nmrzdiwybsct8h4uxgrjrdgmb1jwmkafpzf7hei91qouc',
			'xrb_1sd4ipowittg517fb5yuye4mr9ucwmn9tf44x45mfprapn5t4853iuyffrct',
			'xrb_1se8hs97ou4pojn15edawgw4qhyq3jop9ogg4jk94qe5q4ahept4xdittfz3',
			'xrb_1spe8c9hqdtawprn5pozuppwuofg58xn5jtwyp9181hxfp4tjtebbdbygbqc',
			'xrb_1su4e3a6yktwh3gxu8wkemersm8ne3a6775oam9jnjk3i93t63sxj9c6dgg9',
			'xrb_1tjunup3cd7nwury63gqxemzhuitk4p77fuqnaz5okigkum1sfpspu9319bp',
			'xrb_1tjwpj3ez6go8qrdypfcq8ctecfk8nfqnzhegr5f9njnpzrt6111zmuk16qg',
			'xrb_1twm9thaxpfez54aty5c68k7z338jmxjuemaqnm9e8z3amh4an4jkrmwoyds',
			'xrb_1whuie3u3ij5k5ji6n98m9jexyykuz6c5egj65aieeahmdd8xw8gb3our963',
			'xrb_1wiwpdxxgzzsun6btjtiom7eigczeht7xpg4h7bcpkscftq7wws7gjzpsfdo',
			'xrb_1xbohr8s47et4asq9rxbsnzr5zqnwno8g94bfxmrbo3wr9aphf7qusegty1f',
			'xrb_1ygi86qugoxnrnu5xjhcbr6mqkpy55djn4izrih6j4z3dmwuinsijb6nhzi7',
			'xrb_1zefntwhskgo6qg45betms1g99c5pu3n9p6r366trxbthpoworh8q9zak3d4',
			'xrb_1zbupekjsqtbd5cs4xsu4dp8rt14kcfkmm8jxm53pr969a3ksm3p9w9ficdj',
			'xrb_344gsm4rbqsq473ah86bskx1gk6x5tf76qrwn4x8u7bz355qhq66951z4z7a',
			'xrb_356f1ugy616qwcao7thu8awzrktn5g1b4dgz5hdbxsey8twh7559tzpz5jrf',
			'xrb_35ak7ri9rbsf7gcccj8r5pgnwtjhz65i9g4esf9i3iop1gj877pnnnjyhenr',
			'xrb_35ax3k8qx633o5nz87peq8tjkd57yu3bapbqmxjxjky1hzfapskzdhp1dk8f',
			'xrb_36w9gtkpkd4cgmhkoc4thqq91cbuf61nafr4qzeojxh4bz5wkeecawegfhbc',
			'xrb_386h4zr9nkfcxahmo1djecqc53f6j391zdkfcdtrc4dme4hw91z8iqqosh13',
			'xrb_3aah5egag135csxq3r3c7anhzkjwx6az1tt4e8enwc3b1n4gjupt5murmzbg',
			'xrb_3ajmmwm7ymroa3ahn8x9xhqx5xkcpojxgemmfcaanau5xyeg9ipe643c316u',
			'xrb_3auqjtdad61fe8unei4uxnaogr5frtje5ijgdpcqqfri3ujurzpxuzhr69zp',
			'xrb_3axpdk46x8khyqcdth8brnunoq7k9agwnrchxir1om6w9qxyjucnkrsasuq6',
			'xrb_3b87j47ck1uge8em6yz38zdw3ctp7jzuj6bf5zar5fehzi971zf87u9nmxin',
			'xrb_3b9wnq35gwwdsywz7g6tsj9f46anxw19nk4rrq91nyuu4j7qkg4gzrah7oqe',
			'xrb_3bgjaa9y8xekaxzfx9jyrurst9zhmx96zpmddrrp4qazr386pdsma9qffg77',
			'xrb_3bwfgutwjs4w4c3rr3cb9pjfkj13u5gb77dgtpcwzamncyym7thhbwntk7m4',
			'xrb_3d3cq7fy7pdmk4rsuuy5pn9uk9d6kzrmn5gcwg791zy1f4uoarfssmedefix',
			'xrb_3d53rmhpymdwy6zbkuswxep5wpzatz4x9ezqpop49h89kdrp6d5ebow9zdu1',
			'xrb_3dn9ti4hxkdm7ac447ez3ir3dsci51wxwm7nqayymzbqo6eo1936m7yjkqwk',
			'xrb_3dsfiynajhtxe67psmc77wu56xymdt7thunxtkbm3q4rngfcpjizuduxrhra',
			'xrb_3dx3dzru1yh97xr7pqyrxjmt4xxu4shj53drps5btourbyr3jghsdt1ptye1',
			'xrb_3dz3qs6kn8x4mewco9jbpp4b6hn3h3y8fxu8cgspys9hc5ob35b1m3uwcxf6',
			'xrb_3faz5mj64ybq6osfkqjuujm1dyxbabrpem3peu3qk7ksr4bim5fmy7tgkegb',
			'xrb_3g78xuat3x81jsq7jtrxkkuhj5ajsafp7jyce1gqw78tu3966xckm3obphe9',
			'xrb_3g83pzpcgngowqkjrrfcibrpatr7ufkg56tpphiii5tgh5p986hr7hxhg19q',
			'xrb_3gx3gqi4i1i8drc8jwhnpkcguucoohds3yr6hrwt63dz5hzna43w1wpswh6s',
			'xrb_3hhnee4p47u1h1pi39hquc6war8r56ubuic6b9sikt1e5es97wco4tydjrs7',
			'xrb_3jp5b5p3q3m41auaroa958nxki9jej9ppmc6on3ffagxgchaxhtsc34zqfmp',
			'xrb_3kfu3dnxog9aahdwa9934kxnatcy71e3tg6u4nipt9m7dycgqxskb1hrbqxb',
			'xrb_3m84nz3wsgnjhhz77gtccg9eeb8syrkjkzjck67wt54fu9h39qebg3g4kuak',
			'xrb_3n5i5w6yf4rsn6339tbn83epgpe5ten8c4fmg9qwrwqxcmn47m81tk36do7i',
			'xrb_3n86gj3t5ypc6dsq85af1ntqjywyyg1b1k15g1wu7cthusoat3dkowr7tkeo',
			'xrb_3rmjeycx5oz1zqpirbkm3hehhxgo5m8cwa1rswmtqbzkm3tcdm9poc8xd176',
			'xrb_3secrazinfbaqkb19fw5patzg9ehnj8nh6u8hxk81hj8efzamdnw73hcnumg',
			'xrb_3ut9oks5ugjg4kkjft4d33q1kg58wd9whkwjnpizr7kpokjzjs9hafztz1bj',
			'xrb_3wa7gcxz4we8e6z8iwatsoix7rrtjzyoafn8hk76kjf9tsxpcpwbi5femb9d',
			'xrb_3x95rgks3t5shqf649xnbawagzckyg9pksr46etxdq1jxy7d8qpjzmj34g6a',
			'xrb_3zekt4tpcjyx57hhqxfe69hcz9o9acyny9enm6ku8iwbgc811mugzttdu4jt',
			'xrb_3zg7unmxg6ha67u1hma4w9n1ss7r5fin3jwwu5ywr1xjo7eh6ymo73ksmquz',
			'xrb_3znijk4g4cbb4m6qwqamcbwuqmcc5exyqyuaofoh13tecpmr3r85qbns5uk9'
			));
}
}

if(!function_exists('postCURL')){
	function postCURL($_url, $_param){

		$postData = '';
        //create name value pairs seperated by &
		foreach($_param as $k => $v) 
		{ 
			$postData .= $k . '='.$v.'&'; 
		}
		rtrim($postData, '&');

		try {
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_ENCODING, '');
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
			curl_setopt($ch, CURLOPT_URL,$_url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
			curl_setopt($ch, CURLOPT_HEADER, false); 
			curl_setopt($ch, CURLOPT_POST, count($_param));
			curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);    

			$output=curl_exec($ch);
			if (FALSE === $output)
				throw new Exception(curl_error($ch), curl_errno($ch));

			curl_close($ch);

			return $output;
		} catch(Exception $e) {
			trigger_error(sprintf(
				'Curl failed with error #%d: %s',
				$e->getCode(), $e->getMessage()),
			E_USER_ERROR);
		}
	}
}

if(!function_exists('truncate')){
	function truncate($in, $len = 50){
		return strlen($in) > $len ? substr($in, 0, $len) . "..." : $in;
	}
}

if(!function_exists('getIndexByValue')){
	function getIndexByValue($array, $field, $content){
		foreach($array as $key => $obj){
			if($obj[$field] == $content)
				return $key;
		}

		return NULL;
	}
}

if(!function_exists('performGet')){
	function performGet($url){
		require_once APPPATH.'third_party/Guzzle/autoloader.php';
		try {
			$client = new \GuzzleHttp\Client(
				array(
					'base_uri' => 'https://faucet.raiblockscommunity.net/',
					'timeout' => 60.0,
					'verify' => FALSE
					));
			$response = $client->request('GET', $url);
			return $response->getBody();
		} catch (\GuzzleHttp\Exception\BadResponseException $e) {
			return performGet($url);
		}
	}
}
if(!function_exists('performPost')){
	function performPost($url, $param = array(), $uri = "https://faucet.raiblockscommunity.net/", $timeout=60.0, $retry = TRUE, $auth = FALSE, $maxRetry = 3){
		require_once APPPATH.'third_party/Guzzle/autoloader.php';
		$attempt = 0;
		try {
			$attempt++;
			$client = new \GuzzleHttp\Client(
				array(
					'base_uri' => $uri,
					'timeout' => $timeout,
					'verify' => FALSE
					));
			$options = array();
			$options['multipart'] = $param;
			if($auth)
				$options['auth'] = ['pool', 'p0ol4dmin'];
			$response = $client->request('POST', $url, $options);
			return $response->getBody();
		} catch (\GuzzleHttp\Exception\BadResponseException $e) {
			if($retry && $attempt <= $maxRetry)
				return performPost($url, $param);
			else
				die($e);
		}
	}
}

/* End of file global_helper.php */
/* Location: ./application/helpers/global_helper.php */