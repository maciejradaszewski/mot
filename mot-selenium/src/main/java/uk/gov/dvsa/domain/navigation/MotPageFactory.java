package uk.gov.dvsa.domain.navigation;

import org.openqa.selenium.support.PageFactory;
import uk.gov.dvsa.framework.config.webdriver.MotAppDriver;
import uk.gov.dvsa.ui.pages.Page;

import java.lang.reflect.Constructor;
import java.lang.reflect.InvocationTargetException;

public class MotPageFactory extends PageFactory {

    public static <T extends Page> T newPage(MotAppDriver driver, Class<T> clazz) {
        return getNewPageInstance(driver, clazz);
    }

    private static <T> T getNewPageInstance(MotAppDriver driver, Class<T> clazz) {
        try {
            Constructor<T> constructor = clazz.getConstructor(MotAppDriver.class);
            constructor.setAccessible(true);
            return constructor.newInstance(driver);
        } catch (InvocationTargetException e) {
            e.printStackTrace();
            throw new RuntimeException( String.format( "Attempting to construct page [%s] resulted in an InvocationTargetException: %s", clazz.getSimpleName(), e.getCause() ) );
        } catch (NoSuchMethodException e) {
            e.printStackTrace();
            throw new RuntimeException( String.format( "Attempting to construct page [%s] resulted in an NoSuchMethodException: %s", clazz.getSimpleName(), e.getCause() ) );
        } catch (InstantiationException e) {
            e.printStackTrace();
            throw new RuntimeException( String.format( "Attempting to construct page [%s] resulted in an InstantiationException: %s", clazz.getSimpleName(), e.getCause() ) );
        } catch (IllegalAccessException e) {
            e.printStackTrace();
            throw new RuntimeException( String.format( "Attempting to construct page [%s] resulted in an IllegalAccessException: %s", clazz.getSimpleName(), e.getCause() ) );
        }
    }
}
