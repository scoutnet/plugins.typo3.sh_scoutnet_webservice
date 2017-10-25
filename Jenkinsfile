pipeline {
    agent any

    environment {
        TYPO3_PATH_WEB = '/opt/typo3/web'
    }

    stages {
        stage('Test'){
            steps {
                sh 'docker run --rm -e TYPO3_PATH_WEB -w /opt/typo3 -v `pwd`:/opt/typo3/web/typo3conf/ext/sh_scoutnet_webservice -i scoutnet/cihost:latest vendor/bin/phpunit --color -c web/typo3conf/ext/sh_scoutnet_webservice/Tests/Builds/UnitTests.xml'
                sh 'docker run --rm -e TYPO3_PATH_WEB -w /opt/typo3/web/typo3conf/ext/sh_scoutnet_webservice -v `pwd`:/opt/typo3/web/typo3conf/ext/sh_scoutnet_webservice -i scoutnet/cihost:latest sh -c "find . -name \\*.php | parallel --gnu php -d display_errors=stderr -l {}"'
            }
        }
        stage('Build'){
            steps {
                sh "echo test"
            }
        }
        stage('Deploy'){
            /*when {
                expression {
                    env.TAG_NAME ==~ /(?i)(v[1234567890][.][1234567890][.][1234567890])/
                }
            }*/
            steps {
                withCredentials([string(credentialsId: 'GITHUB_TOKEN', variable: 'GITHUB_TOKEN'), usernamePassword(credentialsId: 'ac854e35-e62e-4aa1-b7ac-2ced736da9e6', passwordVariable: 'TYPO3_TER_PASSWORD', usernameVariable: 'TYPO3_TER_USER')]) {
                    sh 'env'
                    sh 'docker run --rm -e TYPO3_TER_PASSWORD -e TYPO3_TER_USER -e GITHUB_TOKEN -w /opt/data -v `pwd`:/opt/data -i scoutnet/buildhost:latest make checkVersion'
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