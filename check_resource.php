<?php

class PrettyOutput {
    const SEP_MIN = 3;
    const SEP_MAX = 50;
    public static function sep($len = 10) {
        $len = intval($len);
        $len = $len < self::SEP_MIN ? self::SEP_MIN : ($len > self::SEP_MAX ? self::SEP_MAX : $len);
        echo PHP_EOL;
        for ($i=0; $i<$len; ++$i) {
            echo '-';
        }
        echo PHP_EOL;
    }
    public static function raw($result_arr) {
        foreach ($result_arr as $str) {
            echo $str.PHP_EOL;
        }
    }
}

class AutoPushStaticResource {

    private $path;

    function __construct($path = '/usr/local/github/static_resource')
    {
        $this->path = $path;
    }

    private function sepOutput() {
        echo PHP_EOL;
        for ($i=0; $i<10; ++$i) {
            echo '-';
        }
        echo PHP_EOL;
    }

    private function rawOutput($resultArr=[]) {
        foreach ($resultArr as $str) {
            echo $str.PHP_EOL;
        }
    }

    //检测git状态 true继续 false 退出
    private function checkGitStatus() {
        echo getcwd() .PHP_EOL;
        $cmd = "git status";
        exec($cmd, $status);
        $this->rawOutput($status);
        $no_need_run =  ($status[4] == "nothing to commit, working tree clean");
        if ($no_need_run) {
            echo 'working tree clean' . PHP_EOL;
            exit();
        }
    }

    private function gitPull() {
        echo getcwd() .PHP_EOL;
        $cmd = "git pull origin master";
        exec($cmd, $status);
        $this->rawOutput($status);
    }

    private function gitAddAll() {
        echo getcwd() .PHP_EOL;
        $cmd = "git add -A";
        exec($cmd, $status);
        $this->rawOutput($status);
    }

    private function gitCommitAndPush() {
        $this->sepOutput();
        $commit_id = md5(time());
        $cmd = "git commit -m '{$commit_id}'";
        exec($cmd, $status);
        $this->rawOutput($status);
        $cmd = "git push origin master";
        exec($cmd, $status);
        $this->rawOutput($status);
        $this->sepOutput();
    }

    private function stopConcurrency() {
        $cmd = "ps aux|grep check_resource|grep -v grep|wc -l";
        exec($cmd, $count);
        $count = intval($count[0]);
        if($count>1) {
            die(PHP_EOL.'concurrency conflict'.PHP_EOL);
        }
    }

    public function main() {
        $this->stopConcurrency();
        sleep(10);
        die('quit normal');
        sleep(10);
        chdir($this->path);
        $this->gitAddAll();
        $this->checkGitStatus();
        $this->gitPull();
        $this->gitAddAll();
        $this->gitCommitAndPush();
    }


}

class gitOperation {

    private $work_dir;

    function __construct($work_dir=null)
    {
        if (empty($work_dir)) {
            $this->work_dir = getcwd();
        } else {
            if(!is_dir($work_dir)) {
                throw new Exception("{$workdir} is not a dir");
            }
            $this->work_dir = $work_dir;
        }
        chdir($this->work_dir);
    }

    public function gitStatus() {
        echo getcwd();
        $cmd = "git status";
        exec($cmd, $status);
        PrettyOutput::sep(50);
        PrettyOutput::raw($status);
    } 
}


//$obj = new AutoPushStaticResource;
//$obj->main();

$git_op = new GitOperation;
$git_op->gitStatus();
