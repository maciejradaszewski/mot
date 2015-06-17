package com.dvsa.mot.selenium.framework;

import org.openqa.selenium.WebDriver;

import java.util.concurrent.TimeUnit;

public class WebDriverCache {

    private final WebDriver cachedDriver;

    private final Thread hook;

    public WebDriverCache(Configurator configurator) {
        DriverFactory driverFactory = new DriverFactory();

        cachedDriver = driverFactory.getDriver(configurator);

        cachedDriver.manage().timeouts()
                .implicitlyWait(configurator.getDefaultDriverTimeout(), TimeUnit.SECONDS);
        cachedDriver.manage().deleteAllCookies();

        hook = closeWebdriverOnShutdown();
    }

    private Thread closeWebdriverOnShutdown() {
        if (null != this.hook) {
            throw new IllegalStateException();
        }
        Thread hookForShutdown = new Thread(new Runnable() {

            @Override public void run() {
                cachedDriver.quit();
            }

        });
        Runtime.getRuntime().addShutdownHook(hookForShutdown);
        return hookForShutdown;
    }

    public void destroy() {
        // Test failed, so nuke browser in case it's in a bad state.
        cachedDriver.quit();
        Runtime.getRuntime().removeShutdownHook(hook);
    }

    public WebDriver getDriver() {
        return cachedDriver;
    }

}
