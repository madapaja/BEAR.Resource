<?php
//
//namespace MyVendor\Sandbox\Resource\Page {
//
//    use BEAR\Resource\ResourceObject;
//
//    class Index extends ResourceObject
//    {
//        public $name;
//
//        public function onGet($name)
//        {
//            $this->name = $name;
//
//            return $this;
//        }
//    }
//}
//
//namespace Another\Resource\Page {
//
//    use BEAR\Resource\ResourceObject;
//
//    class Index extends ResourceObject
//    {
//        public function onGet()
//        {
//            return $this;
//        }
//    }
//}
//
//namespace BEAR\Resource {
//
//    use BEAR\Resource\Module\JsonModule;
//    use BEAR\Resource\Module\ResourceModule;
//    use BEAR\Resource\Adapter\Http as HttpAdapter;
//    use Doctrine\Common\Annotations\AnnotationReader;
//    use Ray\Di\AbstractModule;
//    use Ray\Di\Injector;
//    use Ray\Di\InjectorInterface;
//    use Ray\Di\Di\Inject;
//    use Ray\Di\Module\InjectorModule;
//    use BEAR\Resource\Module\HalModule;
//
//    class AnotherAppModule extends AbstractModule
//    {
//        protected function configure()
//        {
//            $this->bind()->annotatedWith('app_name')->toInstance('Another');
//            $this->install(new ResourceModule('MyVendor\Sandbox'));
//        }
//    }
//
//    class SchemeModifyModule extends AbstractModule
//    {
//        protected function configure()
//        {
//            $this->install(new ResourceModule('MyVendor\Sandbox'));
//            $this->requestInjection(__NAMESPACE__ . '\Modify')->modify();
//        }
//    }
//
//    class Modify
//    {
//        private $schemeCollection;
//
//        /**
//         * @param SchemeCollectionInterface $schemeCollection
//         * @Inject
//         */
//        public function __construct(SchemeCollectionInterface $schemeCollection)
//        {
//            $this->schemeCollection = $schemeCollection;
//        }
//
//        public function modify()
//        {
//            $this->schemeCollection->scheme('https')->host('*')->toAdapter(new HttpAdapter);
//        }
//    }
//
//    class MyApp
//    {
//        public $injector;
//        public $resource;
//
//        /**
//         * @Inject
//         */
//        public function __construct(
//            InjectorInterface $injector,
//            ResourceInterface $resource
//        ) {
//            $this->injector = $injector;
//            $this->resource = $resource;
//        }
//    }
//
//    class ResourceModuleTest extends \PHPUnit_Framework_TestCase
//    {
//        /**
//         * @var \Ray\Di\AbstractModule
//         */
//        private $module;
//
//        protected function setUp()
//        {
//            $this->module = new ResourceModule('MyVendor\Sandbox');
//        }
//
//        public function testResourceModule()
//        {
//            $resource = (new Injector($this->module))->getInstance('BEAR\Resource\ResourceInterface');
//            $this->assertInstanceOf('BEAR\Resource\Resource', $resource);
//
//            return $resource;
//        }
//
//        public function testResourceModuleCreateResourceObject()
//        {
//            $resource = (new Injector($this->module))->getInstance('BEAR\Resource\ResourceInterface');
//            $page = $resource->get->uri('page://self/index')->withQuery(['name' => 'koriym'])->eager->request();
//            $this->assertInstanceOf('MyVendor\Sandbox\Resource\Page\Index', $page);
//
//            return $page;
//        }
//
//        /**
//         * @depends testResourceModuleCreateResourceObject
//         */
//        public function testResourceQuery($page)
//        {
//            $this->assertSame('koriym', $page->name);
//        }
//
//        public function testCreateResourceObjectOfAnotherApplication()
//        {
//            $resource = (new Injector(new AnotherAppModule))->getInstance('BEAR\Resource\ResourceInterface');
//            $page = $resource->get->uri('page://self/index')->eager->request();
//            $this->assertInstanceOf('Another\Resource\Page\Index', $page);
//        }
//
//        public function testApp()
//        {
//            $app = (new Injector(new SchemeModifyModule))->getInstance('BEAR\Resource\MyApp');
//            /** @var $app \BEAR\Resource\MyApp */
//            $page = $app->resource->get->uri('page://self/index')->withQuery(['name' => 'koriym'])->eager->request();
//            $this->assertInstanceOf('MyVendor\Sandbox\Resource\Page\Index', $page);
//
//            return $app;
//        }
//
//        /**
//         * @depends testApp
//         */
//        public function TODO_TestAppDependencyModification($app)
//        {
//            $app->resource->get->uri('https://www.example.com')->eager->request();
//        }
//
//        public function testEvaluateAsStringWithJsonModule()
//        {
//            $resource = (new Injector(new JsonModule(new ResourceModule('FakeVendor\Sandbox'))))->getInstance('BEAR\Resource\ResourceInterface');
//            $user = $resource->get->uri('app://self/link/user')->withQuery(['id' => 1])->eager->request();
//            $j = (string) $user;
//            $expected = '{"name":"Aramis","age":16,"blog_id":12}';
//            $this->assertSame($expected, (string) $user);
//        }
//
//        public function testHal()
//        {
//            $resource = (new Injector(new HalModule(new ResourceModule('FakeVendor\Sandbox'))))->getInstance('BEAR\Resource\ResourceInterface');
//            $user = $resource->get->uri('app://self/link/user')->withQuery(['id' => 1])->eager->request();
//            $expected = '{
//    "name": "Aramis",
//    "age": 16,
//    "blog_id": 12,
//    "_links": {
//        "self": {
//            "href": "/api/link/user?id=1"
//        }
//    }
//}';
//            $this->assertSame($expected, (string) $user);
//
//            return $user;
//        }
//
//        /**
//         * @param $user
//         *
//         * @depends testHal
//         */
//        public function testHalThenBodyElement($user)
//        {
//            $name =  $user['name'];
//            $this->assertSame($name, 'Aramis');
//        }
//
//        /**
//         * @param $user
//         *
//         * @depends testHal
//         */
//        public function testHalThenCode($user)
//        {
//            $this->assertSame($user->code, 200);
//        }
//        }
//}
