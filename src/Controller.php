<?php namespace XREmitter;
use \stdClass as PhpObj;

class Controller extends PhpObj {
    protected $repo;
    public static $routes = [
        'course_viewed' => 'CourseViewed',
        'discussion_viewed' => 'DiscussionViewed',
        'module_viewed' => 'ModuleViewed',
        'attempt_started' => 'AttemptStarted',
        'attempt_completed' => 'AttemptCompleted',
        'user_loggedin' => 'UserLoggedin',
        'user_loggedout' => 'UserLoggedout',
        'assignment_graded' => 'AssignmentGraded',
        'assignment_submitted' => 'AssignmentSubmitted',
        'user_registered' => 'UserRegistered',
        'enrolment_created' => 'EnrolmentCreated',
        'scorm_launched' => 'ScormLaunched',
    ];

    /**
     * Constructs a new Controller.
     * @param Repository $repo
     */
    public function __construct(Repository $repo) {
        $this->repo = $repo;
    }

    /**
     * Creates a new event.
     * @param [String => Mixed] $opts
     * @return [String => Mixed]
     */
    public function createEvent(array $opts) {
        $route = isset($opts['recipe']) ? $opts['recipe'] : '';
        if (isset(static::$routes[$route])) {
            $event = '\XREmitter\Events\\'.static::$routes[$route];
            $service = new $event($this->repo);
            $opts['context_lang'] = $opts['context_lang'] ?: 'en';
            $statement = $service->read($opts);
            return $service->create($statement);
        } else {
            return null;
        }
    }
}
