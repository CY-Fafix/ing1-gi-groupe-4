import com.fasterxml.jackson.databind.ObjectMapper;
import com.fasterxml.jackson.core.JsonProcessingException;
import com.sun.net.httpserver.HttpExchange;
import com.sun.net.httpserver.HttpHandler;
import com.sun.net.httpserver.HttpServer;

import java.io.BufferedReader;
import java.io.FileReader;
import java.io.IOException;
import java.io.InputStream;
import java.io.OutputStream;
import java.net.InetSocketAddress;
import java.util.concurrent.ThreadPoolExecutor;
import java.util.HashMap;
import java.util.Map;
import java.util.concurrent.Executors;
import java.util.logging.Logger;

public class Serveur {
    // logger pour trace
    private static final Logger LOGGER = Logger.getLogger( Serveur.class.getName() );
    private static final String SERVEUR = "localhost"; // url de base du service
    private static final int PORT = 8001; // port serveur
    private static final String URL = "/test"; // url de base du service
    // boucle principale qui lance le serveur sur le port 8001, à l'url test
    public static void main(String[] args) {
        HttpServer server = null;
        try {
            server = HttpServer.create(new InetSocketAddress(SERVEUR, PORT), 0);

            server.createContext(URL, new  MyHttpHandler());
            ThreadPoolExecutor threadPoolExecutor = (ThreadPoolExecutor) Executors.newFixedThreadPool(10);
            server.setExecutor(threadPoolExecutor);
            server.start();
            LOGGER.info(" Server started on port " + PORT);

        } catch (IOException e) {
            e.printStackTrace();
        }
        
    }

    private static class MyHttpHandler implements HttpHandler {
        /**
         * Manage GET request param
         * @param httpExchange
         * @return first value
         */
        private String handleGetRequest(HttpExchange httpExchange) {
            return httpExchange.getRequestURI()
                    .toString()
                    .split("\\?")[1]
                    .split("=")[1];
        }
        
        /**
         * Manage POST request param
         * @param httpExchange
         * @return le json avec toutes les informations
         */
        private String handlePostRequest(HttpExchange httpExchange) {
            Map<String, Object> map = new HashMap<>(); //elements du json
            Map<String, Object> map2 = new HashMap<>(); //elements recherches du json
            String python = ""; //contient le code python a analyser
        	String requestBody = ""; //contient les arguments en post
        	
        	//On recupere les éléments en post
            try {
                requestBody = getRequestPayload(httpExchange.getRequestBody());
            } catch (IOException e) {
                e.printStackTrace();
            }
            //On recupere le code python
            try {
                python = readFileToString("fichier.py");
            } catch (IOException e) {
                System.err.println("Error reading Python file: " + e.getMessage());
            }
            
            //On ajoute les fonctions principales de comptage au json
            map.put("nbLignes", Fonctions.nbLignes(python));
            map.put("nbFonc", Fonctions.nbFonctions(python));
            map.put("nbMin", Fonctions.nbMin(python));
            map.put("nbMax", Fonctions.nbMax(python));
            map.put("nbMoy", Fonctions.nbMoy(python));

            //On ajoute les nombres d'occurrence des mots demandés s'il y en a
            if (!requestBody.equals("{\"mots\":[]}")) {
	            for (int i=0; i<requestBody.split(":")[1].split(",").length; i++) {
	            	map2.put(requestBody.split(":")[1].split(String.valueOf('"'))[i*2+1], Fonctions.nbOcc(python, requestBody.split(":")[1].split(String.valueOf('"'))[i*2+1]));
	            }
            }
            
            map.put("mots", map2); //ajout des mots recherches
           
            String json = ""; //string qu'on va renvoyer a partir de notre map
            
            ObjectMapper mapper = new ObjectMapper(); //permet la construction du json
            try {
                json = mapper.writeValueAsString(map);
            } catch (JsonProcessingException e) {
                e.printStackTrace();
            }
            return json;
        }
        
        private String getRequestPayload(InputStream inputStream) throws IOException {
            StringBuilder stringBuilder = new StringBuilder();
            int byteRead;
            while ((byteRead = inputStream.read()) != -1) {
                stringBuilder.append((char) byteRead);
            }
            return stringBuilder.toString();
        }
        
        /**
         * Generate simple response html page
         * @param httpExchange
         * @param requestParamVaue
         */
        private void handleResponse(HttpExchange httpExchange, String requestParamValue)  throws  IOException {
            OutputStream outputStream = httpExchange.getResponseBody();
            StringBuilder htmlBuilder = new StringBuilder();
            htmlBuilder.append("<?php $valeurs = json_decode('" + requestParamValue + "', true); ?>"); //true permet d'avoir un objet de type array
            // encode HTML content
            String htmlResponse = htmlBuilder.toString();
            System.out.println(htmlResponse);
            // this line is a must
            httpExchange.sendResponseHeaders(200, htmlResponse.length());
            outputStream.write(htmlResponse.getBytes());
            outputStream.flush();
            outputStream.close();
        }

        // Interface method to be implemented
        @Override
        public void handle(HttpExchange httpExchange) throws IOException {
            LOGGER.info(" Je réponds");
            String requestParamValue=null;
            if("GET".equals(httpExchange.getRequestMethod())) {
                requestParamValue = handleGetRequest(httpExchange);
            } else if("POST".equals(httpExchange.getRequestMethod())) {
                requestParamValue = handlePostRequest(httpExchange);
            }
            handleResponse(httpExchange,requestParamValue);

        }
        
        //Permet de transformer le fichier python en un String
        public static String readFileToString(String filePath) throws IOException {
            StringBuilder contentBuilder = new StringBuilder();
            
            try (BufferedReader reader = new BufferedReader(new FileReader(filePath))) {
                String line;
                while ((line = reader.readLine()) != null) {
                    contentBuilder.append(line);
                    contentBuilder.append(System.lineSeparator());
                }
            }
            
            return contentBuilder.toString();
        }
    }
    
}
