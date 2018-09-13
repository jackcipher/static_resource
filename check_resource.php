<?php


class AutoPushStaticResource {

    private $path;

    function __construct($path = '/usr/local/github/static_resource')
    {
        $this->path = $path;
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
        echo getcwd() .PHP_EOL;
        $commit_id = md5(time());
        $cmd = "git commit -m '{$commit_id}' && git push origin master";
        exec($cmd, $status);
        print_r($status);
    }

    public function main() {
        chdir($this->path);
        $this->gitAddAll();
        $this->checkGitStatus();
        $this->gitPull();
        $this->gitAddAll();
        $this->gitCommitAndPush();
    }


}


$obj = new AutoPushStaticResource;
$obj->main();
