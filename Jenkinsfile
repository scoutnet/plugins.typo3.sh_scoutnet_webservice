pipeline {
    agent any

    stages {
        stage('Test'){
            steps {
                sh 'docker run --rm -w /opt/typo3 -v `pwd`:/opt/typo3/web/typo3conf/ext/sh_scoutnet_webservice -i scoutnet/cihost:latest "vendor/bin/phpunit --color -c web/typo3conf/ext/sh_scoutnet_webservice/Tests/Builds/UnitTests.xml"'
                sh 'docker run --rm -w /opt/typo3/web/typo3conf/ext/sh_scoutnet_webservice -v `pwd`:/opt/typo3/web/typo3conf/ext/sh_scoutnet_webservice -i scoutnet/cihost:latest "find . -name \\*.php | parallel --gnu php -d display_errors=stderr -l {}"'
            }
        }
        stage('Build'){
            steps {
                sh "echo test"
            }
        }
        stage('Deploy'){
            when {
                expression {
                    env.TAG_NAME ==~ /(?i)(v[1234567890][.][1234567890][.][1234567890])/
                }
            }
            steps {
                withCredentials([usernamePassword(credentialsId: '89505d3f-4830-48fe-9595-b84743c5bb79', passwordVariable: 'DOCKER_PASSWORD', usernameVariable: 'DOCKER_USERNAME')]) {
                    sh 'docker login -u="$DOCKER_USERNAME" -p="$DOCKER_PASSWORD"'

                }
            }
        }
        stage('Notify') {
            steps {
                slackSend color: 'good', message: 'Building sh_scoutnet_webservice: Done'
            }
        }

    }
    /*
- name: check_version
  tag: ^[0-9]+[.][0-9]+[.][0-9]+$
  service: build_host
  command: bash -c "cd /opt/data; make checkVersion"

- name: release to Github
  tag: ^[0-9]+[.][0-9]+[.][0-9]+$
  service: build_host
  command: bash -c "cd /opt/data; make release"

- name: build
  tag: ^[0-9]+[.][0-9]+[.][0-9]+$
  service: build_host
  command: bash -c "cd /opt/data; make deploy"
  */
}
