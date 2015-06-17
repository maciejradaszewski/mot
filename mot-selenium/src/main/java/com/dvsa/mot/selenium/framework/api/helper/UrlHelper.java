package com.dvsa.mot.selenium.framework.api.helper;

import org.glassfish.jersey.client.ClientConfig;

import javax.ws.rs.client.ClientBuilder;
import javax.ws.rs.client.WebTarget;

public class UrlHelper {

    public static WebTarget buildWebTargetUrl(String url) {
        return ClientBuilder.newClient(new ClientConfig()).target(url);
    }
}
