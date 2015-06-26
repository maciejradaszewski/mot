package uk.gov.dvsa.framework.config.webdriver;

import uk.gov.dvsa.framework.config.Configurator;

import java.util.concurrent.TimeUnit;

public class WebDriverConfigurator extends Configurator {

    private final MotAppDriver cachedDriver;

    private final Thread hook;

    public WebDriverConfigurator() {
        DriverFactory driverFactory = new DriverFactory();

        cachedDriver = driverFactory.getDriver(this);

        cachedDriver.manage().timeouts()
                .implicitlyWait(getDefaultDriverTimeout(), TimeUnit.SECONDS);
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

    public MotAppDriver getDriver() {
        return cachedDriver;
    }
}
