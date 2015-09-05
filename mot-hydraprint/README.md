# MOT-HYDRAPRINT

## WORKER and HOUSEKEEPING jobs

1. What is that?

    The worker is software responsible for processing certificate generation requests and storing certificates at Amazon S3. After processing, the request row in DB is updated with necessary information to retrieve the certificate.

2. What do I need to run it ?
    
    Java 8 installed at your lamp-mot VM. Running startup script(see below) will ensure Java 8 is installed there i.e. if it is NOT there, it will be provisioned using yum package manager available by default.

3. Why does it have to be run from Vagrant if I have Java 8 already installed locally?
    
    You are right, but the worker has to communicate with MySQL database available at Vagrant and current port-forwarding configuration is not set to forward 3306. When the new Vagrant set-up is available it will most likely have the worker provisioned directly on to VM.

4. How can I start the worker?

    Run ``./service.sh start-worker``. The script ensures there is only once instance running 
at the same time. Every launch stops previously running worker.

5. I can see odd things happening when I first run the script. Can you tell me what is going on there?

    When the script is run, first it tries to check if the artefact pointed in artefact.source file is available locally. If it is not, the script tries to fetch it. If the artefact is corrupted (MD5 signature is different), then it also tries to fetch it. Upon successful download, the script tries to ensure Java 8 is available. All those checks are performed to minimise the effort of updating developers with newer version of the software.

6. What is that JAR file that is downloaded when I start the worker? Cannot that just be a part of the repository?

    It is an artefact that is released in isolation and this is how it can be integrated locally by developers without putting everything into one repo making it big. It is not part of the repository because it is generally not recommended to bloat code repository with binary artefacts.

7. How can I stop the worker?

    Run ``./service.sh stop-worker``. The script kills any processes related to the worker.

8. How can I check if worker is already running?
    
    Run ``./service.sh worker-status``. If the worker is running, you should see details of its process.

9. Something is not working as expected, how can I check what is going on?

    The worker is configured to write logs in ``logs/`` folder, which is created upon worker startup.

10. Something wrong happened and the worker changed the status of the generation request to *FAILED* or left it in status *PENDING*. Why can I do to make the worker process that request again?
    
    There are two commands than are part of mot-hydraprint that can revert the status of the job from *FAILED*/*PENDING* to *NEW*.

    *PENDING* -> *NEW*: ``./service.sh reset-pending``

    *FAILED* -> *NEW*: ``./service.sh reset-failed``

11. Assuming a new version of the worker is available, what should I do to update developers with the new version?
    
    Please update the link in artefact.source and send out an email to developers to inform them about a new version.