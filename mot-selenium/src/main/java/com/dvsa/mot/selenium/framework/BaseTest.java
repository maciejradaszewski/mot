package com.dvsa.mot.selenium.framework;

import com.dvsa.mot.selenium.datasource.Login;
import com.dvsa.mot.selenium.datasource.Person;
import com.dvsa.mot.selenium.datasource.Site;
import com.dvsa.mot.selenium.datasource.Vehicle;
import com.dvsa.mot.selenium.framework.api.*;
import com.dvsa.mot.selenium.framework.api.vehicle.DefaultVehicleDataRandomizer;
import com.dvsa.mot.selenium.framework.api.vehicle.IVehicleDataRandomizer;
import com.dvsa.mot.selenium.framework.errors.UnauthorisedError;
import org.apache.commons.lang3.RandomStringUtils;
import org.joda.time.DateTime;
import org.joda.time.format.DateTimeFormat;
import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.testng.ITestResult;
import org.testng.annotations.AfterMethod;
import org.testng.annotations.BeforeMethod;
import org.testng.annotations.Listeners;

import java.lang.reflect.InvocationTargetException;
import java.util.Arrays;
import java.util.Collection;
import java.util.Date;
import java.util.List;
import java.util.concurrent.TimeUnit;

@Listeners(InfoLoggingListener.class) public abstract class BaseTest extends Configurator {

    protected WebDriver driver = null;
    protected String vtsName;

    protected static final ThreadLocal<WebDriverCache> webDriverCache =
            new ThreadLocal<WebDriverCache>();
    protected Login login = null;
    protected static final IVehicleDataRandomizer vehicleDataRandomizer =
            new DefaultVehicleDataRandomizer();

    private void logoutExistingSession(WebDriver driver) {
        //turn off
        driver.manage().timeouts().implicitlyWait(500, TimeUnit.MILLISECONDS);
        List<WebElement> elements = driver.findElements(By.id("logout"));
        //turn On
        driver.manage().timeouts().implicitlyWait(getDefaultDriverTimeout(), TimeUnit.SECONDS);
        if (elements.size() > 0) {
            elements.get(0).click();
            System.out.println("Logged out existing session.");
        }
    }

    @BeforeMethod(alwaysRun = true) public void setupBaseTest() {

        vtsName = RandomStringUtils.randomAlphabetic(4).toUpperCase();

        if (login == null) {
            login = createTester();
        }
        if (null == webDriverCache.get()) {
            webDriverCache.set(new WebDriverCache(this));
        }
        driver = webDriverCache.get().getDriver();
        logoutExistingSession(driver);
        driver.get(baseUrl());
        driver.manage().deleteAllCookies();
        // Switch authentication toggle on
        if (isToggleAuthenticationEnabled()) {
            driver.get(openAmUrl());
        } else {
            // Switch authentication toggle off
            driver.get(baseUrl());
        }
    }

    @AfterMethod(alwaysRun = true) public void tearDown(ITestResult result) throws Exception {
        if (result.isSuccess()) {
            if (null != driver) {
                driver.manage().deleteAllCookies();
            }
        } else {
            // Take screenshot on test failure
            if (result.getStatus() == ITestResult.FAILURE && isErrorScreenshotEnabled()) {
                Utilities.takeScreenShot(driver,
                        result.getTestClass().getName().replace("com.dvsa.mot.selenium.", "") + "."
                                + result.getName() + "_" + screenshotDateFormat.format(new Date())
                                + ".png", getErrorScreenshotPath() + "/" + getBuildNumber());
            }

            WebDriverCache cachedDriver = webDriverCache.get();
            if (null != cachedDriver) {
                Utilities.Logger.LogError("Tearing down webdriver because of test failure");
                cachedDriver.destroy();
                webDriverCache.set(null);
            }
            driver = null;
        }
    }

    public Vehicle createVehicle(Vehicle vehicle) {
        if (getCreateVehicleViaApi()) {
            return new VehicleApi().createVehicle(vehicle, vehicleDataRandomizer);
        } else {
            return vehicle;
        }
    }

    /**
     * @param vehicle
     * @param v5c
     * @param firstSeen
     * @param lastSeen
     * @param mot1LegacyId
     */
    public void addV5C(Vehicle vehicle, String v5c, DateTime firstSeen, DateTime lastSeen,
            String mot1LegacyId) {
        new V5CCreationApi().addV5C(vehicle, v5c, firstSeen, lastSeen, mot1LegacyId);
    }

    /**
     * Creates a tester or returns the supplied one, depending on configuration.
     * <p/>
     * Only suitable currently for Tester1 - does not clone the supplied tester; the
     * new tester is an active tester at VTS number 1.
     *
     * @return a Login for that tester
     */
    public Login createTester() {
        return createTester(Arrays.asList(1));
    }

    public Login createTester(boolean claimReq) {
        return createTester(Arrays.asList(1), claimReq);
    }

    public Login createTester(Collection<Integer> vtsIds) {
        return new TesterCreationApi().createTester(vtsIds);
    }

    public Person createTesterAsPerson(Collection<Integer> vtsIds) {
        return new TesterCreationApi().createTesterAsPerson(vtsIds);
    }

    public Person createTesterAsPerson(Collection<Integer> vtsIds, boolean claimReq) {
        return new TesterCreationApi().createTesterAsPerson(vtsIds, claimReq);
    }

    public Login createTester(Collection<Integer> vtsIds, boolean claimReq) {
        return new TesterCreationApi().createTester(vtsIds, claimReq);
    }

    public Login createTester(Collection<Integer> vtsIds, TestGroup testGroup, boolean claimReq) {
        return new TesterCreationApi().createTester(vtsIds, testGroup, claimReq);
    }

    public Login createTester(Collection<Integer> vtsIds, TestGroup testGroup, boolean claimReq,
        boolean passwordChangeRequired) {
        return new TesterCreationApi().createTester(vtsIds,testGroup,claimReq, passwordChangeRequired);
    }

    /**
     * Create an AE with the given Organisation name (to be prepended with 'Test Organisation' by the TestSupport API)
     */
    public int createAE(String aeName) {
        return new AeCreationApi().createAe(aeName, Login.LOGIN_AREA_OFFICE1);
    }

    /**
     * Create an AEDM for the given Organisation ID
     */
    public Login createAEDM(int aeId, Login login, boolean claimReq) {
        return new AedmCreationApi().createAedm(aeId, login, claimReq);
    }

    /**
     * Create an Site Manager for the given Site IDs
     */
    public Login createSM(Collection<Integer> siteIds, Login person, String diff) {
        return new SiteManagerCreationApi().createSm(siteIds, person, diff);
    }

    /**
     * Create a VTS for the given AE
     *
     * @return VTS id
     */
    public int createVTS(int aeId, TestGroup testGroup, Login login, String vtsName) {
        return new VtsCreationApi().createVts(aeId, testGroup, login, vtsName);
    }

    /**
     * Create a Vehicle Examiner
     *
     * @return Login
     */
    public Login createVE() {
        return new VehicleExaminerUserCreationApi().createVehicleExaminerUser();
    }

    /**
     * Create Mot Test
     *
     * @param login
     * @param site
     * @param vehicle
     * @param mileage
     * @param testOutcome
     * @return
     */
    public String createMotTest(Login login, Site site, Vehicle vehicle, int mileage,
            MotTestApi.TestOutcome testOutcome) {
        return createMotTest(login, site, vehicle, mileage, testOutcome, DateTime.now());
    }

    public String createMotTest(Login login, Site site, Vehicle vehicle, int mileage,
            MotTestApi.TestOutcome testOutcome, DateTime issuedDate) {
        MotTestApi motTestApi = new MotTestApi();
        MotTestApi.MotTestData motTestData =
                new MotTestApi.MotTestData(testOutcome, mileage, issuedDate);
        String motTestNumber = motTestApi
                .createTest(login, vehicle, Integer.valueOf(site.getId()), motTestData, null);
        return motTestNumber;
    }

    protected int createSpecialNotice(DateTime dateTime, boolean isPublished, String title) {
        return new SpecialNoticeCreationApi().create(dateTime.toString(DateTimeFormat.forPattern("YYYY-MM-dd")), isPublished, title);
    }

    protected void broadcastSpecialNotice(String username, int specialNoticeContentId,
            boolean isAcknowledged) {
        new SpecialNoticeCreationApi().broadcast(username, specialNoticeContentId, isAcknowledged);
    }

    protected boolean hasPermissionsToSeePage(Class<?> pageObjectClass, String url) {
        driver.get(url);
        try {
            pageObjectClass.getConstructor(WebDriver.class).newInstance(driver);
            return true;
        } catch (InvocationTargetException e) {
            if (e.getCause() instanceof UnauthorisedError) {
                return false;
            }

            throw new RuntimeException(e);
        } catch (Exception e) {
            throw new RuntimeException(e);
        }
    }

    protected String getTestClassName() {
        return getClass().getSimpleName();
    }
}
