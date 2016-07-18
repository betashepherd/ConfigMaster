package utils
import (
    "fmt"
    "encoding/json"
    "io/ioutil"
)

var Config_map = make(map[string]string)


func Parse_Json(js string) map[string]string {
	result := make(map[string]string)
	var data map[string]interface{}
	json.Unmarshal([]byte(js), &data)
	for key,_ := range data {
		value := data[key].(string)
		result[key] = value
	}
	return result
}


func Load_Config(config_name string) bool {
	result, err := ioutil.ReadFile(config_name)
	if err != nil {
		fmt.Println(err)
		Error.Printf("load config:%s error:%s", config_name,err)
		return false
	}
	Config_map = Parse_Json(string(result))
	return true
}

func Get_Config(key string) string{
	value, ok := Config_map[key]
	if ok {
		return value
	} else {
		fmt.Printf("key:%s doesn't exist in config", key)
		Warning.Printf("key:%s doesn't exist in config", key)
		return ""
	}
}

// func struct_Str(key string) string




// type Config struct {
// 	Server string "json:server"
// 	Port string "json:port"
// 	Version_query string `json:version_query`
// 	Version_obtain string `json:version_obtain`
// 	Version_recorder string "json:version_recorder"
// 	Query_interval string "json:query_interval"
// }

// type V_recorder struct {
// 	Version string "json:version"
// 	Path string "json:path"
// 	Md5 string "json:md5"
// }



// func Parse_conf(config_str string, cfg interface{}) (map[string]string, bool) {
// 	// var cfg Config
// 	err := json.Unmarshal([]byte(config_str), &cfg)
// 	if err != nil {
// 		fmt.Printf("err is %v\n", err)
// 		return nil,false
// 	}

// 	result := make(map[string]string)

// 	// Config_map["server"] = cfg.Server
// 	// Config_map["port"] = cfg.Port
// 	// Config_map["version_query_url"] = cfg.Url.Version_query
// 	// Config_map["version_obtain_url"] = cfg.Url.Version_obtain
// 	// Config_map["version_recorder_path"] = cfg.Version_recorder_path

// 	//convert struct to map
// 	vt := reflect.TypeOf(cfg)
// 	vv := reflect.ValueOf(cfg)
// 	for i :=  0; i < vt.NumField(); i++ {
// 		key := vt.Field(i).Name
// 		value := vv.FieldByName(key).String()
// 		fmt.Println(key,value)
// 		result[vt.Field(i).Name] = value
// 	}
// 	return result,true
// }

















