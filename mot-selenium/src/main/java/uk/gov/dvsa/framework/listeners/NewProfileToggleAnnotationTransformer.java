package uk.gov.dvsa.framework.listeners;

import org.testng.IAnnotationTransformer;
import org.testng.annotations.ITestAnnotation;
import uk.gov.dvsa.helper.ConfigHelper;

import java.lang.reflect.Constructor;
import java.lang.reflect.Method;

public class NewProfileToggleAnnotationTransformer implements IAnnotationTransformer {
    @Override
    public void transform(ITestAnnotation iTestAnnotation, Class aClass, Constructor constructor, Method method) {
        if(iTestAnnotation.getTestName().equals("NewProfile")){
            iTestAnnotation.setEnabled(ConfigHelper.isNewPersonProfileEnabled());
        }

        if(iTestAnnotation.getTestName().equals("Legacy")){
            iTestAnnotation.setEnabled(!ConfigHelper.isNewPersonProfileEnabled());
        }
    }
}
