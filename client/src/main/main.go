package main

import (
    "utils"
    "fmt"
    "net/http"
    "io/ioutil"
    "encoding/json"
    "time"
    "strconv"
)
const retry_time int = 3

func Send_Request(url string) (string,bool) {
	retry_count := 0
	content := ""
	for retry_count < retry_time {
		retry_count += 1
		resp, err := http.Get(url)
		if err != nil {
		   	utils.Error.Println(err)
		    continue
		}
		defer resp.Body.Close()
		body, err := ioutil.ReadAll(resp.Body)
		if err != nil {
	    	utils.Error.Println(err)
		    continue
		}
	    content = string(body)
	    break
	}
	if content != "" {
		return content, true
	} else {
		return "", false
	}
}

type version_recorder_t struct {
	Version string
	// Md5 string
	Path string
}

const path_and_name  string = "../cmnews.conf"




const app_id string = "5785fb654dd96213115ee532"


type key_item_t struct {
	key string
	path string
	key_v int
}

func get_key_items(response string) []key_item_t {
	var result []key_item_t
	var response_js map[string]interface{}
	json.Unmarshal([]byte(response), &response_js)
	//parse response,check response code ,if ok, get keys
	if data, ok := response_js["data"]; ok {
		data_js := data.([]interface{})
		for _, data_item := range data_js {
			data_item_map := data_item.(map[string]interface{})
			key_val := data_item_map["key"].(string)
			path_val := data_item_map["path"].(string)
			v_val := int(data_item_map["ver"].(float64))
			item := key_item_t{key:key_val,path:path_val, key_v:v_val}
			result = append(result, item)
		}
	}
	return result
}

func get_conf_items_of_key(response string) (content string, path string) {
	// var result []key_conf_t
	var response_js map[string]interface{}
	json.Unmarshal([]byte(response), &response_js)
	//parse response,check response code ,if ok, get keys
	if data, ok := response_js["data"]; ok {
		data_js := data.(map[string]interface{})
		key_val := data_js["val"].(string)
		path_val := data_js["path"].(string)
		return key_val, path_val
		// item := key_conf_t{conf_content:key_val, path:path_val}
		// result = append(result, item)
	}
	return "",""
}


func main() {
    // read conf file
    path := "../config/config_distributor.conf"
    ok := utils.Load_Config(path)
    if !ok {
    	return
    }
    log_file := utils.Get_Config("Log_path")//"logger.txt"

    utils.Logger_init(log_file)
    //query for the lastest version every 'Query_interval' seconds
    query_interval_str := utils.Get_Config("Query_interval")
    query_interval,err := strconv.Atoi(query_interval_str)
    // fmt.Println(query_interval)
    if err != nil {
    	utils.Error.Println("get query interval error")
    	return
    }

    version_recorder_dir := utils.Get_Config("Version_recorder")



    for true {

    	appcfg_url := "http://" + utils.Get_Config("Server") + ":" + 
	    	utils.Get_Config("Port") +utils.Get_Config("Version_query") + "?id=" + app_id
	    // fmt.Println(appcfg_url)

	    appcfg_response := ""
	    appcfg_response, ok = Send_Request(appcfg_url)
	    key_items := get_key_items(appcfg_response)
	    if len(key_items) == 0 {
	    	utils.Error.Printf("key item is empty, appcfg_url:%s",appcfg_url)
	    	//continue
	    }
	    for _, key_item := range key_items {
	    	//read version recorder to check if it is the latest version
	    	need_update := false
	    	version_recorder_file_name := app_id + "_" + key_item.key + ".txt"
	    	version_recorder_file_path := version_recorder_dir + version_recorder_file_name
	    	version_recorder_file_exist := utils.Is_file_exist(version_recorder_file_path)
	    	if version_recorder_file_exist == false {
	    		need_update = true
	    		utils.Trace.Printf("need update cause version_recorder_file:%s doesn't exist", version_recorder_file_path)
	    	} else {
	    		version_recorder_content := utils.Read_File(version_recorder_file_path)
			    version_recorder_map := utils.Parse_Json(version_recorder_content)
			    current_version := version_recorder_map["Version"]
			    current_version_int, _ := strconv.Atoi(current_version)
			    if key_item.key_v != current_version_int {
			    	need_update = true
			    	utils.Trace.Printf("need update cause new version:%d doesn't equal old version:%d", current_version_int,key_item.key_v)
			    }
	    	}
	    	
	    	if need_update == false {
	    		utils.Trace.Printf("need not update, key:%s,v:%d",key_item.key, key_item.key_v)
	    		continue
	    	} else {
	    		utils.Trace.Printf("need update, key:%s,v:%d",key_item.key, key_item.key_v)
	    	}
	    	// getcfg_url aims to get concrete config content
	    	getcfg_url := "http://" + utils.Get_Config("Server") + ":" + 
	    					utils.Get_Config("Port") +utils.Get_Config("Version_obtain") + 
	    					"?id=" + app_id +"&k=" + key_item.key + "&v=" + strconv.Itoa(key_item.key_v)
	    	getcfg_response, ok := Send_Request(getcfg_url)
	    	if ok == false {
	    		utils.Error.Printf("get response of key:%s,%s,%d error",key_item.key, key_item.path, key_item.key_v)
	    		continue
	    	}
	    	key_conf_content, key_conf_path := get_conf_items_of_key(getcfg_response)
	    	fmt.Println(key_conf_content, key_conf_path)
	    	utils.Write_to_file(key_conf_path, key_conf_content)

	    	//update version recorder
	    	content_to_record := version_recorder_t{Version:strconv.Itoa(key_item.key_v),Path:key_conf_path}
	    	content_to_record_js,err := json.Marshal(content_to_record)
	    	if err != nil {
	    		utils.Error.Println("update version error:",err)
	    		continue
	    	}
	    	ok = utils.Write_to_file(version_recorder_file_path,string(content_to_record_js))
	    	if ok {
	    		utils.Trace.Println("Update success,",key_item.key, key_item.key_v)
	    	}
	    } 
	    time.Sleep(time.Duration(query_interval) * time.Second)

	    fmt.Println("client heart beats")
	    utils.Trace.Println("client heart beats")
	}
}
















