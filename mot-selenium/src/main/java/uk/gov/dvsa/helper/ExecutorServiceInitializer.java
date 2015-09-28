package uk.gov.dvsa.helper;

import java.util.concurrent.Callable;
import java.util.concurrent.ExecutorService;
import java.util.concurrent.Executors;
import java.util.concurrent.TimeUnit;

public class ExecutorServiceInitializer {
    public void runInParallel(int iterations, int threadPool, int terminationTimeout, Callable<Void> task) throws Exception {
        ExecutorService service = Executors.newFixedThreadPool(threadPool);

        for (int i=0; i<iterations; i++)
            service.submit(task);
        service.shutdown();
        service.awaitTermination(terminationTimeout, TimeUnit.SECONDS);
    }
}
