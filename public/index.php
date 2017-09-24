<?php

use Phalcon\Loader;
use Phalcon\Mvc\Micro;
use Phalcon\Di\FactoryDefault;
use Phalcon\Db\Adapter\Pdo\Mysql as PdoMysql;
use Phalcon\Http\Response;
use Phalcon\HTTP\Request;
use Phalcon\Filter;
use Phalcon\Mvc\Model\Query;

// Устанавливаем константы пути прилождения
define('BASE_PATH', dirname(__DIR__));
define('APP_PATH', BASE_PATH . '/app');

// Регистрируем автолоадер
$loader = new Loader();

$loader->registerDirs(
    [
        APP_PATH . '/models/',
    ]
);

$loader->register();

$di = new FactoryDefault();

// Установка сервиса базы данных
$di->set(
    'db',
    function () {
        return new PdoMysql(
            [
                "host"     => "127.0.0.1",
                "username" => "root",
                "password" => "",
                "dbname"   => "rest"
            ]
        );
    }
);

// Создаем и привязываем DI к приложению
$app = new Micro($di);

//Роут для тестов
$app->post(
    "/test",
    function () use ($app) {

        $request = new Request();

        if ($request->isPost()) {

            $dataPassangerId = $request->getPost('passanger_id');
            $dataToken = $request->getPost('access_token');
            $dataUserLocation = $request->getPost('user_location');
            $dataCarId = $request->getPost('car_id');
            $dataDriverId = $request->getPost('driver_id');
            $dataCountryId = $request->getPost('country_id');
            $dataPassPhone = $request->getPost('pass_phone');
            $dataRegionId = $request->getPost('region_id');
            $dataRoutePoints = $request->getPost('route_points');
            $dataStartTime = $request->getPost('start_time');
            $dataPassCount = $request->getPost('pass_count');
            $dataCallme = $request->getPost('callme');
            $dataLarge  =$request->getPost('large');
            $dataPets = $request->getPost('pets');
            $dataWishListOptionId = $request->getPost('wishlist_option_id');
            $dataBabyChair = $request->getPost('baby_chair');
            $dataPaymentType = $request-> getPost('payment_type_id');
            $dataDefferedPayment = $request->getPost('deffered_payment');
            $dataDuration = $request->getPost('duration');
            $dataExtension = $request->getPost('extension');
            $dataComment = $request->getPost('comment');

            if ($dataToken != 'E@3dkCRjzjN9pskGA2~Ya4?mmPgwvI{K82yz') {
                $response = new Response();
                $response->setJsonContent(
                    [
                        'Error' => 'Invalid access token: ' . $dataToken
                    ]
                );
                return $response;
            }

        if (!isset($dataPassangerId) || !isset($dataUserLocation) || !isset($dataCarId) || !isset($dataDriverId) || !isset($dataCountryId) || !isset($dataPassPhone) || !isset($dataRegionId) || !isset($dataRoutePoints) || !isset($dataStartTime) || !isset($dataPassCount) || !isset($dataCallme) || !isset($dataLarge) || !isset($dataPets) || !isset($dataWishListOptionId) || !isset($dataBabyChair) || !isset($dataPaymentType) || !isset($dataDefferedPayment) || !isset($dataDuration)
            || !isset($dataExtension) || !isset($dataComment)) {

            $response = new Response();
            $response->setJsonContent(
                [
                    'Error' => 'Incorrect parameters of method'
                ]
            );
            return $response;
        }

        echo 'Пишем логику заказа';


        } else {

            $response = new Response();
            $response->setJsonContent(
                [
                    'Error' => 'Method is not defined'
                ]
            );
            return $response;

        }
    }
);

//Дефолтный роут
$app->get(
    '/',
    function() {
        $response = new Response();
        $response->setJsonContent(
            [
                'Message' => 'Wellcome to the REST-sevice',
            ]
        );
        return $response;
    }
);

//Роут для авторизации
$app->post(
    "/apiv1/auth",
    function () use ($app) {

        $request = new Request();

        if ($request->isPost()) {

            $key = $request->getPost('key');
            if ($key != 'rNkJGSL1sg@Jbz@iFWV8|4fB5lP{n#Z%HGGQtQOb') {
                $response = new Response();
                $response->setJsonContent(
                    [
                        'Error' => 'Invalid key',
                    ]
                );
                return $response;
            }
            $phone = $request->getPost('phone');
            $password = $request->getPost('password');
        }

        $user = Users::findFirstByPhone($phone);

        if ($user) {

            if ($user->user_status == 'active') {
                $response = new Response();
                $response->setJsonContent(
                    [
                        'Error' => 'You are already logged in',
                    ]
                );
                return $response;

            } else if ($user->password != md5($password)) {

                $response = new Response();
                $response->setJsonContent(
                    [
                        'Error' => 'Send valid password',
                    ]
                );
                return $response;

            } else {

                $user->user_status = 'active';
                $user->save();

                $response = new Response();
                $response->setJsonContent(
                    [
                        'user_id' => $user->getId(),
                        'access_token' => $user->getToken(),
                        'user_status' => $user->user_status
                    ]
                );
                return $response;
            }
        } else {
            $response = new Response();
            $response->setJsonContent(
                [
                    'Error' => 'For starting need registration'
                ]
            );
            return $response;
        }

    }
);

//Роут для регистрации
$app->post(
    "/apiv1/registration",
    function () use ($app) {

        $request = new Request();

        if ($request->isPost()) {

            $key = $request->getPost('key');

            if ($key != 'rNkJGSL1sg@Jbz@iFWV8|4fB5lP{n#Z%HGGQtQOb') {
                $response = new Response();
                $response->setJsonContent(
                    [
                        'Error' => 'Invalid key',
                    ]
                );
                return $response;
            }

            $name = $request->getPost('name');
            $country_id = $request->getPost('country_id');
            $password = $request->getPost('password');
            $phone = $request->getPost('phone');

        }

        $user = new Users();

        $user->name = $name;
        $user->country_id = $country_id;
        $user->setPassword($password);
        $user->phone = $phone;

        if ($user->save() === false ) {
            $messages = $user->getMessages();
            foreach ($messages as $message) {
                echo $message;
            }
        } else {
            $user = Users::find();
            $user = $user->getLast();
            $token = substr(md5(rand(0, mt_getrandmax())), 0, 16);
            $user->setToken($token);
            $user->save();

            $response = new Response();

            $response->setJsonContent(
                [
                    'user_id' => $user->getId(),
                    'access_token' => $user->getToken()
                ]
            );

            return $response;
        }
    }
);

//Роут для регистрации заказа в системе
$app->post(
    "/apiv1/addOrder",
    function () {
        echo "<h1>post - добавление заказа</h1>";
    }
);

//Роут для установки статуса заказа между водителем и пассажиром
$app->put(
    "/apiv1/setOrderStatus",
    function () {
        echo "<h1>put - статус заказа</h1>";
    }
);

//Роут для отображения карты
$app->get(
    "/apiv1/getMapInfo",
    function () use ($app) {

        $request = new Request();

        if ($request->isGet()) {

            $dataLat = $_GET['lat'];
            $dataLng = $_GET['lng'];
            $dataToken = $_GET['access_token'];

            if ($dataToken != 'E@3dkCRjzjN9pskGA2~Ya4?mmPgwvI{K82yz') {
                $response = new Response();
                $response->setJsonContent(
                    [
                        'Error' => 'Invalid access token: '. $dataToken
                    ]
                );
                return $response;
            }

            if ( !isset($dataLat) || !isset($dataLng) ) {
                $response = new Response();
                $response->setJsonContent(
                    [
                        'Error' => 'Incorrect parameters of method'
                    ]
                );
                return $response;
            }

            $query = new Query(
                "SELECT * FROM Cars WHERE status = 1",
                $this->getDI()
            );

            $cars = $query->execute();

            $data = [];

            foreach ($cars as $key => $car) {

                $data['cars'][$key] = [
                    'id' => $car->id,
                    'driver_id' => $car->drivers->id,
                    'status' => $car->status,
                    'color' => $car->color,
                    'direction' => $car->direction,
                    'reg_number' => $car->reg_number,
                    'year' => $car->year,
                    'brand' => $car->brand,
                    'model' => $car->model,
                    'currency' => 'frn',
                    'planting_costs' => '32',
                    'driver_phone' => $car->drivers->phone,
                    'car_photo' => $car->car_photo,
                    'costs_per_1' => '2',
                    'location' => [
                        'lat' => $car->lat,
                        'lng' => $car->lng
                    ]
                ];
            }
            $response = new Response();
            $response->setJsonContent($data);
            return ($response);

        } else {
            $response = new Response();
            $response->setJsonContent(
                [
                    'Error' => 'Method is not defined'
                ]
            );
            return $response;
        }
    }
);

//Роут для 404-ого статуса
$app->notFound(
    function () use ($app) {

        $response = new Response();
        $response->setJsonContent(
            [
                'Error' => 'Send the correct request'
            ]
        );
        return $response;
    }
);

$app->handle();