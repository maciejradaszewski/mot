package uk.gov.dvsa.ui;

import com.dvsa.mot.selenium.framework.InfoLoggingListener;
import com.dvsa.mot.selenium.framework.Utilities;
import org.testng.ITestResult;
import org.testng.annotations.*;
import uk.gov.dvsa.data.*;
import uk.gov.dvsa.domain.navigation.PageNavigator;
import uk.gov.dvsa.domain.workflow.RoleAssociationWorkflow;
import uk.gov.dvsa.domain.workflow.VehicleReInspectionWorkflow;
import uk.gov.dvsa.framework.config.Configurator;
import uk.gov.dvsa.framework.config.webdriver.MotAppDriver;
import uk.gov.dvsa.framework.config.webdriver.WebDriverConfigurator;

import java.util.Date;

@Listeners(InfoLoggingListener.class)
public abstract class BaseTest {

    private MotAppDriver driver = null;
    protected AeData aeData = new AeData();
    protected SiteData siteData = new SiteData();
    protected UserData userData = new UserData();
    protected VehicleData vehicleData = new VehicleData();
    protected MotApi motApi = new MotApi();
    protected MotUI motUI;

    protected static final ThreadLocal<WebDriverConfigurator> webDriverConfigurator =
            new ThreadLocal<>();

    protected PageNavigator pageNavigator = new PageNavigator();
    private RoleAssociationWorkflow roleAssociationWorkflow = new RoleAssociationWorkflow();
    private VehicleReInspectionWorkflow vehicleReInspectionWorkflow = new VehicleReInspectionWorkflow();

    public RoleAssociationWorkflow roleAssociationWorkflow() {
        return roleAssociationWorkflow;
    }
    public VehicleReInspectionWorkflow vehicleReinspectionWorkflow() {
        return vehicleReInspectionWorkflow;
    }

    @BeforeMethod(alwaysRun = true)
    public void setupBaseTest() {
        if (null == webDriverConfigurator.get()) {
            webDriverConfigurator.set(new WebDriverConfigurator());
        }

        driver = webDriverConfigurator.get().getDriver();
        pageNavigator.setDriver(driver);
        motUI = new MotUI(driver);
        roleAssociationWorkflow.setDriver(driver);
        vehicleReInspectionWorkflow.setDriver(driver);

        driver.setBaseUrl(Configurator.baseUrl());
        driver.loadBaseUrl();
        driver.manage().deleteAllCookies();
    }

    @AfterMethod(alwaysRun = true)
    public void tearDown(ITestResult result) {
        if (result.isSuccess()) {
            if (null != driver) {
                driver.manage().deleteAllCookies();
            }
        } else {
            // Take screenshot on test failure
            if (result.getStatus() == ITestResult.FAILURE && Configurator.isErrorScreenshotEnabled()) {
                Utilities.takeScreenShot(driver,
                        result.getTestClass().getName().replace("com.dvsa.mot.selenium.", "") + "."
                                + result.getName() + "_" + Configurator.screenshotDateFormat.format(new Date())
                                + ".png", Configurator.getErrorScreenshotPath() + "/" + Configurator.getBuildNumber());
            }

            WebDriverConfigurator cachedDriver = webDriverConfigurator.get();
            if (null != cachedDriver) {
                Utilities.Logger.LogError("Tearing down webdriver because of test failure");
                cachedDriver.destroy();
                webDriverConfigurator.set(null);
            }
            driver = null;
        }
    }
}
