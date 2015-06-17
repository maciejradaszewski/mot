package com.dvsa.mot.selenium.framework;

import org.testng.ITestListener;
import org.testng.ITestResult;
import org.testng.TestListenerAdapter;

/**
 * Prints out details about test execution
 *
 * @author art
 */
public class InfoLoggingListener extends TestListenerAdapter implements ITestListener {

    // Keep these the same length for easier-to-read output
    private static final String RUNNING = "Running:             ";
    private static final String SKIPPED = "SKIPPED (%8.1f)s: ";
    private static final String FAILURE = "FAILURE (%8.1f)s: ";
    private static final String SUCCESS = "Success (%8.1f)s: ";

    @Override public void onTestStart(ITestResult result) {
        super.onTestStart(result);
        Utilities.Logger.LogInfo(RUNNING + printable(result));
    }

    @Override public void onTestSuccess(ITestResult result) {
        super.onTestSuccess(result);
        Utilities.Logger.LogInfo(String.format(SUCCESS, duration(result)) + printable(result));
    }

    @Override public void onTestFailure(ITestResult result) {
        super.onTestFailure(result);
        Utilities.Logger.LogInfo(String.format(FAILURE, duration(result)) + printable(result));
    }

    @Override public void onTestSkipped(ITestResult result) {
        super.onTestSkipped(result);
        Utilities.Logger.LogInfo(String.format(SKIPPED, duration(result)) + printable(result));
    }

    @Override public void onTestFailedButWithinSuccessPercentage(ITestResult result) {
        super.onTestFailedButWithinSuccessPercentage(result);
        Utilities.Logger.LogInfo("Failure within success %: " + printable(result));
    }

    private String printable(ITestResult result) {
        return result.getTestClass().getName() + "." + result.getName();
    }

    private float duration(ITestResult result) {
        return (result.getEndMillis() - result.getStartMillis()) / 1000f;
    }
}
