<?php


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
        $count = intval($count);
        echo $count;
        if($count>1) {
            die('concurrency conflict');
        }
    }

    public function main() {
        $this->stopConcurrency();
        sleep(10);
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
