package utils
import (
	"os"
	// "fmt"
	"io/ioutil"
	"path"
)

// to check the existence of file and dir
func Is_file_exist(name string) bool {
	_, err := os.Stat(name)
	return err == nil || os.IsExist(err)
}

func File_rename(old_name string, new_name string) bool {
	file_exist := Is_file_exist(old_name)
	if file_exist {
		//exists
		// fmt.Println("old_name:",old_name," new_name:",new_name)
		err := os.Rename(old_name, new_name)
		if err != nil {
			// fmt.Printf("Rename %s to %s failed\n", old_name, new_name)
			Error.Printf("Rename %s to %s failed\n", old_name, new_name)
			return false
		}
	} else {
		// fmt.Printf("file %s not exists\n", old_name)
		Trace.Printf("file %s not exists\n", old_name)
		return false
	}
	return true
}


/*
*  maybe the function: `func WriteFile(filename string, data []byte, perm os.FileMode) error ` is a better choice
*/
func Write_to_file(name string, content string) bool {
	file_exist := Is_file_exist(name)
	if file_exist {
		File_rename(name, name+"_bak")
	}
	//check whether dir is available
	dir := path.Dir(name)
	dir_exist := Is_file_exist(dir)
	if dir_exist == false {
		os.MkdirAll(dir,0777)
	}
	f, err := os.Create(name)
	defer f.Close()
	if err != nil {
		// fmt.Printf("file:%s create error\n", name)
		Error.Printf("file:%s create error\n", name)
		return false
	}
	_ , err = f.WriteString(content)
	if err != nil {
		// fmt.Printf("write to file err")
		Error.Printf("write to file err")
		return false
	}
	return true
}

func Read_File(name string) string {
	file_exist := Is_file_exist(name)
	if !file_exist {
		Error.Println("file:",name," doesn't exist")
		return ""
	}
	content, err := ioutil.ReadFile(name)
	if err != nil {
		Error.Printf("read file:%s err:%s", name, err)
		return ""
	}
	return string(content)
}











